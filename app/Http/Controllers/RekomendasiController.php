<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Models\Prodi;

class RekomendasiController extends Controller
{
    private function getQuestions()
    {
        // Menyimpan semua pertanyaan beserta kategorinya dalam satu array.
        // Kategori tidak akan ditampilkan ke pengguna.
        return [
            ['id' => 'q1', 'cat' => 'logika', 'text' => 'Saya menikmati aktivitas yang mengharuskan mencari pola atau hubungan antar informasi.'],
            ['id' => 'q2', 'cat' => 'logika', 'text' => 'Saya mampu menemukan penyebab suatu masalah melalui analisis yang sistematis.'],
            ['id' => 'q3', 'cat' => 'logika', 'text' => 'Saya lebih percaya pada fakta dan data daripada dugaan ketika mengambil keputusan.'],
            ['id' => 'q4', 'cat' => 'logika', 'text' => 'Saya senang memecahkan soal yang membutuhkan penalaran logis.'],
            ['id' => 'q5', 'cat' => 'logika', 'text' => 'Saya terbiasa membandingkan beberapa alternatif sebelum menentukan solusi terbaik.'],
            
            ['id' => 'q6', 'cat' => 'sosial', 'text' => 'Saya mudah bekerja sama dengan orang yang memiliki pendapat berbeda.'],
            ['id' => 'q7', 'cat' => 'sosial', 'text' => 'Saya merasa nyaman ketika harus berinteraksi dengan banyak orang baru.'],
            ['id' => 'q8', 'cat' => 'sosial', 'text' => 'Saya sering menjadi tempat teman berdiskusi atau meminta bantuan.'],
            ['id' => 'q9', 'cat' => 'sosial', 'text' => 'Saya dapat menyesuaikan diri dengan lingkungan sosial yang baru.'],
            ['id' => 'q10', 'cat' => 'sosial', 'text' => 'Saya menikmati kegiatan yang melibatkan kerja tim.'],

            ['id' => 'q11', 'cat' => 'kreatif', 'text' => 'Saya sering menemukan cara baru untuk menyelesaikan suatu tugas.'],
            ['id' => 'q12', 'cat' => 'kreatif', 'text' => 'Saya senang mengembangkan ide yang berbeda dari kebanyakan orang.'],
            ['id' => 'q13', 'cat' => 'kreatif', 'text' => 'Saya menikmati kegiatan yang memberi kebebasan untuk berkreasi.'],
            ['id' => 'q14', 'cat' => 'kreatif', 'text' => 'Saya tertarik mencoba pendekatan baru meskipun belum pernah dilakukan sebelumnya.'],
            ['id' => 'q15', 'cat' => 'kreatif', 'text' => 'Saya sering memiliki banyak alternatif solusi untuk satu masalah.'],

            ['id' => 'q16', 'cat' => 'bisnis', 'text' => 'Saya tertarik mempelajari cara memperoleh keuntungan dari suatu produk atau jasa.'],
            ['id' => 'q17', 'cat' => 'bisnis', 'text' => 'Saya sering memperhatikan peluang usaha di sekitar saya.'],
            ['id' => 'q18', 'cat' => 'bisnis', 'text' => 'Saya tertarik mempelajari strategi pemasaran dan pengembangan bisnis.'],
            ['id' => 'q19', 'cat' => 'bisnis', 'text' => 'Saya senang membuat perencanaan untuk mencapai target tertentu.'],
            ['id' => 'q20', 'cat' => 'bisnis', 'text' => 'Saya mempertimbangkan aspek biaya dan manfaat sebelum mengambil keputusan.'],

            ['id' => 'q21', 'cat' => 'sains', 'text' => 'Saya tertarik mengetahui alasan ilmiah di balik suatu fenomena.'],
            ['id' => 'q22', 'cat' => 'sains', 'text' => 'Saya menikmati kegiatan eksperimen atau pengamatan untuk memperoleh informasi.'],
            ['id' => 'q23', 'cat' => 'sains', 'text' => 'Saya tertarik membaca atau menonton materi tentang perkembangan ilmu pengetahuan.'],
            ['id' => 'q24', 'cat' => 'sains', 'text' => 'Saya senang mempelajari bagaimana sesuatu bekerja secara ilmiah.'],
            ['id' => 'q25', 'cat' => 'sains', 'text' => 'Saya tertarik pada mata pelajaran yang berkaitan dengan sains dan teknologi.'],

            ['id' => 'q26', 'cat' => 'komunikatif', 'text' => 'Saya dapat menjelaskan ide saya kepada orang lain dengan jelas.'],
            ['id' => 'q27', 'cat' => 'komunikatif', 'text' => 'Saya merasa percaya diri saat berbicara di depan banyak orang.'],
            ['id' => 'q28', 'cat' => 'komunikatif', 'text' => 'Saya mampu menyampaikan pendapat tanpa menimbulkan kesalahpahaman.'],
            ['id' => 'q29', 'cat' => 'komunikatif', 'text' => 'Saya mudah memulai percakapan dengan orang lain.'],
            ['id' => 'q30', 'cat' => 'komunikatif', 'text' => 'Saya dapat menyesuaikan cara berbicara sesuai dengan lawan bicara.'],

            ['id' => 'q31', 'cat' => 'teliti', 'text' => 'Saya terbiasa memeriksa kembali pekerjaan sebelum dikumpulkan.'],
            ['id' => 'q32', 'cat' => 'teliti', 'text' => 'Saya memperhatikan detail kecil yang sering diabaikan orang lain.'],
            ['id' => 'q33', 'cat' => 'teliti', 'text' => 'Saya berusaha menghindari kesalahan dengan mengikuti prosedur yang benar.'],
            ['id' => 'q34', 'cat' => 'teliti', 'text' => 'Saya menyelesaikan tugas sesuai aturan yang telah ditetapkan.'],
            ['id' => 'q35', 'cat' => 'teliti', 'text' => 'Saya merasa tidak nyaman jika pekerjaan saya masih memiliki kesalahan yang belum diperiksa.'],

            ['id' => 'q36', 'cat' => 'empati', 'text' => 'Saya berusaha memahami perasaan orang lain sebelum menilai tindakan mereka.'],
            ['id' => 'q37', 'cat' => 'empati', 'text' => 'Saya merasa prihatin ketika melihat seseorang mengalami kesulitan.'],
            ['id' => 'q38', 'cat' => 'empati', 'text' => 'Saya mempertimbangkan sudut pandang orang lain saat terjadi perbedaan pendapat.'],
            ['id' => 'q39', 'cat' => 'empati', 'text' => 'Saya bersedia membantu orang lain meskipun tidak diminta secara langsung.'],
            ['id' => 'q40', 'cat' => 'empati', 'text' => 'Saya dapat merasakan ketika seseorang sedang mengalami masalah emosional.'],

            ['id' => 'q41', 'cat' => 'kepemimpinan', 'text' => 'Saya bersedia mengambil tanggung jawab ketika bekerja dalam kelompok.'],
            ['id' => 'q42', 'cat' => 'kepemimpinan', 'text' => 'Saya mampu mengarahkan anggota tim untuk mencapai tujuan bersama.'],
            ['id' => 'q43', 'cat' => 'kepemimpinan', 'text' => 'Saya dapat mengambil keputusan ketika menghadapi situasi yang tidak pasti.'],
            ['id' => 'q44', 'cat' => 'kepemimpinan', 'text' => 'Saya sering menjadi penggerak dalam kegiatan kelompok atau organisasi.'],
            ['id' => 'q45', 'cat' => 'kepemimpinan', 'text' => 'Saya mampu membagi tugas sesuai kemampuan anggota tim.'],
        ];
    }

    public function start()
    {
        $prodis = Prodi::all();
        return view('user.rekomendasi-start', compact('prodis'));
    }

    public function startSubmit(Request $request)
    {
        $request->validate(['minat_jurusan' => 'required']);
        
        session()->put('minat_jurusan_awal', $request->minat_jurusan);
        session()->put('jawaban_kuesioner', []);
        
        return redirect()->route('rekomendasi.kuesioner', ['page' => 1]);
    }

    public function kuesioner(Request $request)
    {
        $page = (int) $request->query('page', 1);
        $perPage = 5;
        $questions = $this->getQuestions();
        
        $totalQuestions = count($questions);
        $totalPages = ceil($totalQuestions / $perPage);
        
        if ($page > $totalPages) {
            return redirect()->route('rekomendasi.hitung');
        }

        $offset = ($page - 1) * $perPage;
        $currentQuestions = array_slice($questions, $offset, $perPage);

        return view('user.kuesioner', compact('currentQuestions', 'page', 'totalPages'));
    }

    public function kuesionerSubmit(Request $request)
    {
        $page = $request->input('page');
        $answers = $request->input('jawaban'); // format: ['q1' => 4, 'q2' => 5]
        
        $savedAnswers = session()->get('jawaban_kuesioner', []);
        foreach ($answers as $id => $val) {
            $savedAnswers[$id] = $val;
        }
        session()->put('jawaban_kuesioner', $savedAnswers);

        return redirect()->route('rekomendasi.kuesioner', ['page' => $page + 1]);
    }

    public function hitungRekomendasi()
    {
        $answers = session()->get('jawaban_kuesioner', []);
        $questions = $this->getQuestions();
        
        // Kelompokkan nilai per kategori
        $scoresByCategory = [];
        foreach ($questions as $q) {
            if (isset($answers[$q['id']])) {
                $scoresByCategory[$q['cat']][] = (int) $answers[$q['id']];
            }
        }

        // Hitung rata-rata
        $categories = ['logika', 'sosial', 'kreatif', 'bisnis', 'sains', 'komunikatif', 'teliti', 'empati', 'kepemimpinan'];
        $averages = [];
        
        foreach ($categories as $cat) {
            $avg = 0;
            if (isset($scoresByCategory[$cat]) && count($scoresByCategory[$cat]) > 0) {
                $avg = array_sum($scoresByCategory[$cat]) / count($scoresByCategory[$cat]);
            }
            $averages[] = round($avg, 2);
        }

        // Gunakan Absolute Path bawaan Laravel
        $scriptPath = base_path('app/Python/predict.py');
        $modelPath = base_path('app/Python/model_rekomendasi.pkl');
        
        // Gabungkan path model sebagai argumen pertama, lalu diikuti nilai rata-rata
        $arguments = array_merge([$modelPath], $averages);
        $command = array_merge(['python', $scriptPath], $arguments);
        
        // Membawa environment variabel bawaan OS
        $env = [
            'SystemRoot' => getenv('SystemRoot') ?: 'C:\\Windows',
            'PATH' => getenv('PATH'),
            'USERPROFILE' => getenv('USERPROFILE')
        ];
        
        $process = new Process($command, null, $env);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $output = json_decode($process->getOutput(), true);
        
        // PENCEGAHAN ERROR: Cek apakah Python mengembalikan error
        if (!$output || isset($output['error'])) {
            $errorMessage = $output['error'] ?? 'Terjadi kesalahan tidak dikenal pada model AI.';
            // Kembalikan pengguna ke halaman kuesioner dengan pesan error
            return redirect()->back()->with('error', 'Gagal memproses prediksi: ' . $errorMessage);
        }
        
        session()->put('hasil_rekomendasi', $output);
        session()->put('skor_kategori', array_combine($categories, $averages));

        return redirect()->route('rekomendasi.hasil');
    }

    public function hasil()
    {
        $hasil = session()->get('hasil_rekomendasi');
        $skorKategori = session()->get('skor_kategori');
        
        // Ambil top 3 dari database
        $top3Names = array_slice(array_column($hasil, 'jurusan'), 0, 3);
        
        // Ambil objek Prodi dari database agar bisa menampilkan icon dll.
        // Field name disesuaikan dengan struktur tabel prodis
        $topProdis = Prodi::whereIn('nama_prodi', $top3Names)->get();
        
        // Mengurutkan koleksi Prodi sesuai urutan dari AI
        $sortedTopProdis = collect($top3Names)->map(function ($name) use ($topProdis) {
            return $topProdis->firstWhere('nama_prodi', $name);
        })->filter();

        // Identifikasi 3 trait tertinggi untuk teks penjelasan
        arsort($skorKategori);
        $topTraits = array_slice(array_keys($skorKategori), 0, 3);

        return view('user.hasil-rekomendasi', compact('hasil', 'sortedTopProdis', 'topTraits'));
    }
}