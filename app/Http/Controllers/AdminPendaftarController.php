<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPendaftar;
use Carbon\Carbon;

class AdminPendaftarController extends Controller
{
    // ==========================================
    // 1. DASHBOARD & STATISTIK ADMIN
    // ==========================================
    public function dashboard(Request $request)
    {
        // 1. Ambil filter dari request URL (Default: 'Bulan Ini')
        $filter = $request->query('filter', 'Bulan Ini');
        
        $query = \App\Models\DataPendaftar::query();
        $now = \Carbon\Carbon::now('Asia/Jakarta');

        // 2. Terapkan logika Filter Waktu pada Database
        if ($filter == 'Hari Ini') {
            $query->whereDate('created_at', $now->today());
        } elseif ($filter == 'Minggu Ini') {
            $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
        } elseif ($filter == 'Bulan Ini') {
            $query->whereMonth('created_at', $now->month)
                  ->whereYear('created_at', $now->year);
        }

        // Ambil data yang sudah difilter
        $pendaftarFiltered = $query->get();

        // 3. Hitung Kartu Statistik (Berdasarkan Filter)
        $stats = [];
        $stats['totalPendaftar'] = $pendaftarFiltered->count();
        
        // Gabungan antara yang belum bayar & yang belum divalidasi berkasnya
        $stats['menungguValidasi'] = $pendaftarFiltered->whereIn('status_pembayaran', ['Menunggu Validasi'])->count() 
                                   + $pendaftarFiltered->whereIn('status_pendaftaran', ['menunggu verifikasi'])->count();
        
        // Hitung yang status kelulusannya mengandung kata 'Lulus'
        $stats['lulusSeleksi'] = $pendaftarFiltered->filter(function($item) {
            return str_contains($item->status_kelulusan, 'Lulus');
        })->count();

        // Hitung Pendapatan (Hanya ambil yang pembayarannya "Terverifikasi")
        $totalPendapatan = $pendaftarFiltered->where('status_pembayaran', 'Terverifikasi')->sum('nominal_biaya');
        // Dibagi 1 Juta karena di View desain Anda formatnya "Rp ... Jt"
        $stats['pendapatan'] = $totalPendapatan / 1000000; 

        // ==========================================
        // 4. Data untuk Grafik (Chart.js)
        // Grafik selalu mengambil data Tahun Ini agar grafiknya terlihat penuh
        // ==========================================
        $semuaPendaftarTahunIni = \App\Models\DataPendaftar::whereYear('created_at', $now->year)->get();
        // Catatan: $now sudah dalam timezone WIB (Asia/Jakarta)

        $jurusanData = $semuaPendaftarTahunIni->groupBy('pilihan_jurusan_1')
            ->map(function ($row) { return $row->count(); })
            ->filter(function ($value, $key) { return !empty($key); });

        $labelsBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataBulan = array_fill(0, 12, 0);
        
        $semuaPendaftarTahunIni->groupBy(function($date) {
            return \Carbon\Carbon::parse($date->created_at)->format('n'); // Ambil index bulan
        })->each(function ($item, $key) use (&$dataBulan) {
            $dataBulan[$key - 1] = $item->count();
        });

        // Lempar semua data ke View
        return view('admin.dashboard', compact('stats', 'filter', 'jurusanData', 'labelsBulan', 'dataBulan'));
    }

    // ==========================================
    // 2. EXPORT DATA EXCEL (CSV)
    // ==========================================
    public function exportCsv()
    {
        $pendaftar = \App\Models\DataPendaftar::orderBy('created_at', 'desc')->get();
        $filename = "Data_Pendaftar_Adzkia_" . date('Y-m-d') . ".csv";
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('ID', 'No Pendaftaran', 'Nama Lengkap', 'NIK', 'Jenis Kelamin', 'Pilihan 1', 'Pilihan 2', 'Jalur', 'Status Pembayaran', 'Status Kelulusan', 'Tanggal Daftar');

        $callback = function() use($pendaftar, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns); // Tulis Header kolom

            foreach ($pendaftar as $row) {
                fputcsv($file, array(
                    $row->id,
                    $row->no_pendaftaran,
                    $row->nama_lengkap,
                    $row->nik,
                    $row->gender,
                    $row->pilihan_jurusan_1,
                    $row->pilihan_jurusan_2,
                    $row->jalur_pendaftaran,
                    $row->status_pembayaran,
                    $row->status_kelulusan ?? 'Belum Ditetapkan',
                    Carbon::parse($row->created_at)->format('d-M-Y')
                ));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function index()
    {
        $users = DataPendaftar::latest()->get(); 
        $totalPendaftar   = DataPendaftar::count();
        $menungguValidasi = DataPendaftar::where('status_pembayaran', 'Menunggu Validasi')->count();
        $lulusSeleksi     = DataPendaftar::where('status_pembayaran', 'Terverifikasi')->count();
        $pembayaranBelum  = DataPendaftar::where('status_pembayaran', 'Belum Bayar')->count();

        return view('admin.pendaftar', compact('users', 'totalPendaftar', 'menungguValidasi', 'lulusSeleksi', 'pembayaranBelum'));
    }
    
    // ==========================================
    // VALIDASI PEMBAYARAN (KEUANGAN)
    // ==========================================
    public function validasiPembayaranIndex(Request $request)
    {
        $query = DataPendaftar::query();

        if ($request->filled('jalur') && $request->jalur != 'Semua Jalur') {
            $query->where('jalur_pendaftaran', $request->jalur);
        }

        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        } else {
            $query->whereIn('status_pembayaran', ['Belum Bayar', 'Menunggu Validasi']);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $pendaftarPending = $query->latest()->get();

        foreach ($pendaftarPending as $data) {
            $tanggalDaftar = $data->created_at ?? now();
            if ($tanggalDaftar->between('2026-01-01', '2026-03-31')) $gelombang = 'Gelombang 1';
            elseif ($tanggalDaftar->between('2026-04-01', '2026-06-30')) $gelombang = 'Gelombang 2';
            else $gelombang = 'Gelombang 3';

            $data->jalur_lengkap = $data->jalur_pendaftaran . ' ' . $gelombang;
            $data->bank_pilihan = $data->metode_pembayaran ?? 'Belum Dipilih'; 
            $data->nominal_biaya = $data->nominal_biaya ?? 250000;
        }

        return view('admin.validasi-pembayaran', compact('pendaftarPending'));
    }

    // Fungsi untuk menyetujui (Sudah ada di controller Anda, pastikan sesuai)
    public function setujuiPembayaran($id)
    {
        $pendaftar = DataPendaftar::findOrFail($id);
        $pendaftar->status_pembayaran = 'Terverifikasi';
        $pendaftar->save();

        $pesan = "*Pembayaran Terverifikasi ✅*\n\n" .
                 "Halo *{$pendaftar->nama_lengkap}*,\n\n" .
                 "Pembayaran biaya pendaftaran Anda (No. {$pendaftar->no_pendaftaran}) telah *BERHASIL DIVERIFIKASI* oleh panitia SPMB Universitas Adzkia.\n\n" .
                 "Silakan login dan lanjutkan ke tahap *pengisian biodata & unggah berkas*:\n" .
                 "http://spmb.adzkia.ac.id/login\n\n" .
                 "Terima kasih.";

        // Panggil fungsi kirimNotifikasiWA internal
        $terkirim = $this->kirimNotifikasiWA($pendaftar->no_whatsapp, $pesan);
        $info = $terkirim ? 'Notifikasi WhatsApp telah dikirim.' : '(Notifikasi WA gagal terkirim, cek koneksi Fonnte).';

        return redirect()->back()->with('success',
            'Pembayaran atas nama ' . $pendaftar->nama_lengkap . ' berhasil diverifikasi. ' . $info);
    }

    // Fungsi BARU untuk menolak
    public function tolakPembayaran($id)
    {
        $pendaftar = DataPendaftar::findOrFail($id);
        // Kembalikan ke awal agar bisa unggah ulang
        $pendaftar->status_pembayaran = 'Belum Bayar';

        if ($pendaftar->bukti_bayar) {
            \Illuminate\Support\Facades\Storage::delete('public/' . $pendaftar->bukti_bayar);
            $pendaftar->bukti_bayar = null;
        }
        $pendaftar->save();

        $pesan = "*Verifikasi Pembayaran Ditolak ⚠️*\n\n" .
                 "Halo *{$pendaftar->nama_lengkap}*,\n\n" .
                 "Mohon maaf, bukti pembayaran Anda (No. {$pendaftar->no_pendaftaran}) *belum dapat kami verifikasi*.\n\n" .
                 "Silakan login kembali lalu *unggah ulang* bukti pembayaran yang valid dan jelas:\n" .
                 "http://spmb.adzkia.ac.id/login\n\n" .
                 "Bila ada kendala, hubungi panitia SPMB. Terima kasih.";

        // Panggil fungsi kirimNotifikasiWA internal
        $terkirim = $this->kirimNotifikasiWA($pendaftar->no_whatsapp, $pesan);
        $info = $terkirim ? 'Notifikasi WA terkirim.' : '(Notifikasi WA gagal terkirim).';

        return redirect()->back()->with('error',
            'Pembayaran ditolak. Pendaftar diminta mengunggah ulang. ' . $info);
    }

    // ==========================================
    // VALIDASI BIODATA (AKADEMIK / VERIFIKATOR)
    // ==========================================
    public function daftarUlangIndex()
    {
        $pendaftarDaftarUlang = DataPendaftar::where('status_pembayaran', 'Terverifikasi')
                                ->whereIn('status_pendaftaran', ['menunggu verifikasi', 'Selesai', 'Revisi'])  
                                ->latest()
                                ->get();

        return view('admin.validasi-daftar-ulang', compact('pendaftarDaftarUlang'));
    }

    public function setujuiDaftarUlang($id)
    {
        $pendaftar = DataPendaftar::findOrFail($id);
        $pendaftar->status_pendaftaran = 'Selesai';
        $pendaftar->save();

        $pesan = "*Biodata & Berkas Terverifikasi ✅*\n\n" .
                 "Halo *{$pendaftar->nama_lengkap}*,\n\n" .
                 "Biodata dan berkas pendaftaran Anda (No. {$pendaftar->no_pendaftaran}) telah *BERHASIL DIVERIFIKASI* oleh tim akademik SPMB Universitas Adzkia.\n\n" .
                 "Selamat! Anda telah menyelesaikan seluruh proses pendaftaran SPMB. Nantikan pengumuman kelulusan melalui dashboard Anda.\n\n" .
                 "Terima kasih.";
                 
        // Panggil fungsi kirimNotifikasiWA internal
        $terkirim = $this->kirimNotifikasiWA($pendaftar->no_whatsapp, $pesan);
        $info = $terkirim ? 'Notifikasi WA terkirim ke peserta.' : '(Notifikasi WA gagal terkirim).';

        // Menambahkan response pesan ke frontend
        return redirect()->back()->with('success', 'Berkas pendaftar telah diverifikasi. ' . $info);
    }

    public function revisiDaftarUlang(Request $request, $id)
    {
        $request->validate(['pesan_revisi' => 'required|string']);
        $pendaftar = DataPendaftar::findOrFail($id);
        $pendaftar->update([
            'status_pendaftaran' => 'Revisi',
            'pesan_revisi'       => $request->pesan_revisi
        ]);

        $pesan = "*Revisi Biodata & Berkas ⚠️*\n\n" .
                 "Halo *{$pendaftar->nama_lengkap}*,\n\n" .
                 "Setelah kami tinjau, biodata atau berkas pendaftaran Anda (No. {$pendaftar->no_pendaftaran}) *memerlukan revisi* agar sesuai dengan persyaratan SPMB Universitas Adzkia.\n\n" .
                 "Berikut adalah pesan dari tim akademik:\n" .
                 "📌 *Pesan Revisi:* {$request->pesan_revisi}\n\n" .
                 "Silakan login kembali dan perbaiki biodata atau berkas Anda sesuai dengan pesan di atas:\n" .
                 "http://spmb.adzkia.ac.id/login\n\n" .
                 "Terima kasih.";
                 
        // Panggil fungsi kirimNotifikasiWA internal
        $terkirim = $this->kirimNotifikasiWA($pendaftar->no_whatsapp, $pesan);
        $info = $terkirim ? 'Notifikasi WA terkirim ke peserta.' : '(Notifikasi WA gagal terkirim).';

        return back()->with('success', 'Pesan revisi berhasil dikirim ke pendaftar. ' . $info);
    }

    // ==========================================
    // MODUL PENGUMUMAN KELULUSAN
    // ==========================================
    public function pengumumanIndex(Request $request)
    {
        $query = DataPendaftar::whereIn('status_pendaftaran', ['Selesai', 'Terverifikasi', 'menunggu verifikasi']);
        
        if ($request->has('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('no_pendaftaran', 'like', '%' . $request->search . '%');
        }

        $pendaftar = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // PASTIKAN BARIS INI MEMANGGIL 'admin.pengumuman' 
        // BUKAN 'admin.pengumuman-kelulusan'
        return view('admin.pengumuman', compact('pendaftar'));
    }

    public function tetapkanKelulusan(Request $request, $id)
    {
        $request->validate([
            'status_kelulusan' => 'required|in:Lulus Pilihan 1,Lulus Pilihan 2,Tidak Lulus',
        ]);

        $pendaftar = DataPendaftar::findOrFail($id);
        $pendaftar->status_kelulusan = $request->status_kelulusan;
        $pendaftar->save();

        $lulus = in_array($request->status_kelulusan, ['Lulus Pilihan 1', 'Lulus Pilihan 2']);

        if ($lulus) {
            $prodiLulus = $request->status_kelulusan === 'Lulus Pilihan 2'
                ? $pendaftar->pilihan_jurusan_2
                : $pendaftar->pilihan_jurusan_1;

            $pesan = "*SELAMAT! Anda Dinyatakan LULUS 🎉*\n\n" .
                     "Halo *{$pendaftar->nama_lengkap}*,\n\n" .
                     "Berdasarkan hasil seleksi SPMB Universitas Adzkia, Anda dinyatakan *LULUS* pada program studi:\n" .
                     "🎓 *{$prodiLulus}*\n\n" .
                     "Silakan login untuk melihat pengumuman resmi dan mengunduh *Surat Kelulusan (LoA)*:\n" .
                     "http://spmb.adzkia.ac.id/login\n\n" .
                     "Sampai jumpa di kampus. Terima kasih.";
        } else {
            $pesan = "*Pengumuman Hasil Seleksi SPMB*\n\n" .
                     "Halo *{$pendaftar->nama_lengkap}*,\n\n" .
                     "Terima kasih atas partisipasi Anda dalam SPMB Universitas Adzkia.\n\n" .
                     "Setelah melalui proses seleksi, dengan berat hati kami sampaikan bahwa Anda *belum berkesempatan* diterima pada periode ini.\n\n" .
                     "Tetap semangat, Anda dapat mencoba kembali pada gelombang berikutnya. Terima kasih.";
        }

        // Panggil fungsi kirimNotifikasiWA internal
        $terkirim = $this->kirimNotifikasiWA($pendaftar->no_whatsapp, $pesan);
        $info = $terkirim ? 'Notifikasi WA terkirim ke peserta.' : '(Notifikasi WA gagal terkirim).';

        return redirect()->back()->with('success',
            'Status kelulusan atas nama ' . $pendaftar->nama_lengkap . ' berhasil ditetapkan. ' . $info);
    }

    public function updateKelulusan(Request $request, $id)
    {
        // Validasi disesuaikan dengan 3 opsi baru
        $request->validate([
            'status_kelulusan' => 'required|in:Lulus Pilihan 1,Lulus Pilihan 2,Tidak Lulus'
        ]);
        
        $pendaftar = DataPendaftar::findOrFail($id);
        $pendaftar->status_kelulusan = $request->status_kelulusan;
        $pendaftar->save();
        
        return redirect()->back()->with('success', 'Status kelulusan berhasil diperbarui!');
    }   

    // ==========================================
    // FUNGSI HELPER WA (Fonnte API Private)
    // ==========================================
    private function kirimNotifikasiWA($nomor, $pesan)
    {
        $token = env('FONNTE_TOKEN', 'oNrEA5wZL2XwgeMtvQwV');
        $url   = 'https://api.fonnte.com/send';

        try {
            // Tembak API Fonnte dengan timeout 5 detik dan matikan verify SSL
            $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                ->timeout(5) 
                ->withHeaders([
                    'Authorization' => $token,
                ])->post($url, [
                    'target'  => $nomor,
                    'message' => $pesan,
                ]);

            if ($response->failed()) {
                \Illuminate\Support\Facades\Log::warning('Fonnte gagal mengirim WA Admin', [
                    'nomor'  => $nomor,
                    'status' => $response->status()
                ]);
                return false;
            }
            
            return true;

        } catch (\Throwable $e) {
            // Jika Fonnte Mati / Timeout, error ditangkap diam-diam di sini!
            \Illuminate\Support\Facades\Log::error('Fonnte Error di Admin: ' . $e->getMessage());
            return false;
        }
    }
}