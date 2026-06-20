<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use App\Models\SoalKuesioner;
use App\Models\RiwayatRekomendasi;
use App\Models\Prodi;

class RekomendasiController extends Controller
{
    // ==========================================
    // 1. TAMPILAN AWAL & SIMPAN JURUSAN MINAT
    // ==========================================
    public function start()
    {
        // Ambil data prodi untuk ditampilkan di halaman awal
        $prodis = Prodi::all();
        return view('user.rekomendasi-start', compact('prodis'));
    }

    public function startSubmit(Request $request)
    {
        $request->validate([
            'minat_jurusan' => 'required'
        ]);

        // Cari nama jurusan berdasarkan ID yang dikirim form
        $prodi = Prodi::find($request->minat_jurusan);
        $namaJurusan = $prodi ? $prodi->nama : 'Belum Diketahui';

        // Simpan nama jurusan yang diminati ke session
        session()->put('minat_jurusan', $namaJurusan);
        
        // Bersihkan riwayat kuesioner sebelumnya (jika ada)
        session()->forget('jawaban_kuesioner');

        return redirect()->route('rekomendasi.kuesioner', ['page' => 1]);
    }

    // ==========================================
    // 2. AMBIL SOAL DARI DATABASE
    // ==========================================
    private function getQuestions()
    {
        $soals = SoalKuesioner::all();
        $formattedSoal = [];
        
        foreach($soals as $soal) {
            $formattedSoal[] = [
                'id'   => 'q' . $soal->id,
                'cat'  => strtolower($soal->kategori),
                'text' => $soal->pertanyaan
            ];
        }
        
        return $formattedSoal;
    }

    // ==========================================
    // 3. LOGIKA KUESIONER & PAGINASI
    // ==========================================
    public function kuesioner(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = 5;
        $questions = $this->getQuestions();

        $totalPages = ceil(count($questions) / $perPage);
        if ($page < 1 || $page > $totalPages) {
            return redirect()->route('rekomendasi.kuesioner', ['page' => 1]);
        }

        $offset = ($page - 1) * $perPage;
        $pagedQuestions = array_slice($questions, $offset, $perPage);
        $savedAnswers = session()->get('jawaban_kuesioner', []);

        return view('user.kuesioner', compact('pagedQuestions', 'page', 'totalPages', 'savedAnswers'));
    }

    public function kuesionerSubmit(Request $request)
    {
        $page = $request->input('page');
        $answers = $request->input('jawaban'); 
        
        $savedAnswers = session()->get('jawaban_kuesioner', []);
        if($answers) {
            foreach ($answers as $id => $val) {
                $savedAnswers[$id] = $val;
            }
        }
        session()->put('jawaban_kuesioner', $savedAnswers);

        $questions = $this->getQuestions();
        $totalPages = ceil(count($questions) / 5);

        // Jika ini halaman terakhir kuesioner, arahkan ke halaman loading
        if ($page >= $totalPages) {
            return redirect()->route('rekomendasi.loading');
        }

        return redirect()->route('rekomendasi.kuesioner', ['page' => $page + 1]);
    }

    // ==========================================
    // 4. HALAMAN LOADING
    // ==========================================
    public function loading()
    {
        return view('user.rekomendasi-loading');
    }

    // ==========================================
    // 5. PROSES AI & SIMPAN KE DATABASE RETRAINING
    // ==========================================
    public function prosesAIAjax()
    {
        $answers = session()->get('jawaban_kuesioner', []);
        $questions = $this->getQuestions();
        
        $scoresByCategory = [];
        $detailKonteksChatbot = []; // Menyimpan teks soal & jawaban untuk konteks Chatbot

        foreach ($questions as $q) {
            if (isset($answers[$q['id']])) {
                $val = (int) $answers[$q['id']];
                $scoresByCategory[$q['cat']][] = $val;
                
                // Simpan untuk diumpankan ke Chatbot Groq nanti
                $detailKonteksChatbot[] = [
                    'kategori'   => ucfirst($q['cat']),
                    'pertanyaan' => $q['text'],
                    'skor'       => $val // 1 (Sangat Tidak Setuju) sampai 5 (Sangat Setuju)
                ];
            }
        }

        // Simpan konteks lengkap ke session untuk Chatbot
        session()->put('konteks_qa_chatbot', $detailKonteksChatbot);

        // Hitung rata-rata
        $categories = ['logika', 'sosial', 'kreatif', 'bisnis', 'sains', 'komunikatif', 'teliti', 'empati', 'kepemimpinan'];
        $averages = [];
        
        foreach ($categories as $cat) {
            $avg = 0;
            if (isset($scoresByCategory[$cat]) && count($scoresByCategory[$cat]) > 0) {
                $avg = array_sum($scoresByCategory[$cat]) / count($scoresByCategory[$cat]);
            }
            $averages[$cat] = round($avg, 2);
        }

        // Eksekusi Model Python Naive Bayes
        $scriptPath = base_path('app/Python/predict.py');
        $modelPath = base_path('app/Python/model_rekomendasi.pkl');
        
        // Kita gunakan array_values agar urutannya berurutan menjadi argumen python
        $arguments = array_merge([$modelPath], array_values($averages));
        $command = array_merge(['python', $scriptPath], $arguments);
        
        $env = [
            'SystemRoot' => getenv('SystemRoot') ?: 'C:\\Windows',
            'PATH' => getenv('PATH'),
            'USERPROFILE' => getenv('USERPROFILE')
        ];
        
        $process = new Process($command, null, $env);
        $process->run();

        if (!$process->isSuccessful()) {
            // Tangkap pesan error asli dari terminal server
            $errorAsli = $process->getErrorOutput(); 
            
            // Catat di log Laravel (storage/logs/laravel.log)
            \Illuminate\Support\Facades\Log::error("Python Error di Production: " . $errorAsli);

            return response()->json([
                'success' => false, 
                'message' => 'Gagal menjalankan script Python AI.',
                'debug_error' => $errorAsli // Menampilkan error asli ke inspect element/network browser
            ], 500);
        }

        $output = json_decode($process->getOutput(), true);
        
        if (!$output || isset($output['error'])) {
            return response()->json(['success' => false, 'message' => $output['error'] ?? 'Error dari model Python.'], 500);
        }
        
        session()->put('hasil_rekomendasi', $output);
        session()->put('skor_kategori', $averages);

        // --- SIMPAN KE DATABASE UNTUK RETRAINING MODEL ---
        RiwayatRekomendasi::create([
            'session_id'           => session()->getId(),
            'jurusan_diminati'     => session()->get('minat_jurusan'),
            'skor_logika'          => $averages['logika'] ?? 0,
            'skor_sosial'          => $averages['sosial'] ?? 0,
            'skor_kreatif'         => $averages['kreatif'] ?? 0,
            'skor_bisnis'          => $averages['bisnis'] ?? 0,
            'skor_sains'           => $averages['sains'] ?? 0,
            'skor_komunikatif'     => $averages['komunikatif'] ?? 0,
            'skor_teliti'          => $averages['teliti'] ?? 0,
            'skor_empati'          => $averages['empati'] ?? 0,
            'skor_kepemimpinan'    => $averages['kepemimpinan'] ?? 0,
            'hasil_rekomendasi_ai' => $output[0]['jurusan'] ?? null, // Top 1 Rekomendasi
        ]);

        return response()->json(['success' => true]);
    }

    // ==========================================
    // 6. HALAMAN HASIL
    // ==========================================
    public function hasil()
    {
        $hasil = session('hasil_rekomendasi');
        if (!$hasil) {
            return redirect()->route('rekomendasi.start')->with('error', 'Silakan isi kuesioner terlebih dahulu.');
        }

        $skorKategori = session('skor_kategori');
        arsort($skorKategori);
        $topTraits = array_keys(array_slice($skorKategori, 0, 3));

        $sortedTopProdis = Prodi::all();

        return view('user.hasil-rekomendasi', compact('hasil', 'topTraits', 'sortedTopProdis'));
    }
}