<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPendaftar;
use App\Support\ActivityLogger;
use Carbon\Carbon;

class AdminPendaftarController extends Controller
{
    // ==========================================
    // DASHBOARD & STATISTIK ADMIN
    // ==========================================
    public function dashboard(Request $request)
    {
        $filter = $request->query('filter', 'Bulan Ini');
        
        $query = DataPendaftar::query();
        $now   = Carbon::now('Asia/Jakarta');

        switch ($filter) {
            case 'Hari Ini':
                $query->whereDate('created_at', $now->toDateString());
                break;
            case 'Minggu Ini':
                $query->whereBetween('created_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]);
                break;
            case 'Tahun Ini':
                $query->whereYear('created_at', $now->year);
                break;
            default: // Bulan Ini
                $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
                break;
        }

        $totalPendaftar   = (clone $query)->count();
        $menungguValidasi = (clone $query)->where('status_pembayaran', 'Menunggu Validasi')->count();
        $sudahVerifikasi  = (clone $query)->where('status_pembayaran', 'Terverifikasi')->count();
        $belumBayar       = (clone $query)->where('status_pembayaran', 'Belum Bayar')->count();
        $totalLulus       = (clone $query)->whereIn('status_kelulusan', ['Lulus Pilihan 1', 'Lulus Pilihan 2'])->count();

        // Data grafik per bulan (semua tahun berjalan)
        $labelsBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
        $dataBulan   = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataBulan[] = DataPendaftar::whereYear('created_at', $now->year)
                ->whereMonth('created_at', $i)->count();
        }

        $jurusanData = DataPendaftar::selectRaw('pilihan_jurusan_1, count(*) as total')
            ->groupBy('pilihan_jurusan_1')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->pluck('total', 'pilihan_jurusan_1');

        $stats = [
            'totalPendaftar'   => $totalPendaftar,
            'menungguValidasi' => $menungguValidasi,
            'lulusSeleksi'     => $totalLulus,
            'pendapatan'       => round(DataPendaftar::where('status_pembayaran', 'Terverifikasi')->sum('nominal_biaya') / 1_000_000, 1),
        ];

        $pendaftarTerbaru = DataPendaftar::latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalPendaftar', 'menungguValidasi', 'sudahVerifikasi', 'belumBayar',
            'totalLulus', 'stats', 'labelsBulan', 'dataBulan', 'jurusanData',
            'pendaftarTerbaru', 'filter'
        ));
    }

    // ==========================================
    // DAFTAR PENDAFTAR (admin.pendaftar)
    // ==========================================
    public function index(Request $request)
    {
        $query = DataPendaftar::latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_lengkap', 'like', "%{$s}%")
                  ->orWhere('no_pendaftaran', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        if ($request->filled('jalur')) {
            $query->where('jalur_pendaftaran', $request->jalur);
        }

        // Variabel yang dibutuhkan view pendaftar.blade.php (nama harus sama persis)
        $totalPendaftar   = DataPendaftar::count();
        $menungguValidasi = DataPendaftar::where('status_pembayaran', 'Menunggu Validasi')->count();
        $lulusSeleksi     = DataPendaftar::whereIn('status_kelulusan', ['Lulus Pilihan 1', 'Lulus Pilihan 2'])->count();
        $pembayaranBelum  = DataPendaftar::where('status_pembayaran', 'Belum Bayar')->count();

        // View menggunakan $users sebagai nama variabel daftar pendaftar
        $users = $query->get();

        return view('admin.pendaftar', compact(
            'users', 'totalPendaftar', 'menungguValidasi', 'lulusSeleksi', 'pembayaranBelum'
        ));
    }

    // ==========================================
    // VALIDASI PEMBAYARAN
    // Nama method HARUS validasiPembayaranIndex (sesuai route web.php)
    // ==========================================
    public function validasiPembayaranIndex(Request $request)
    {
        $query = DataPendaftar::query();

        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        } else {
            $query->whereIn('status_pembayaran', ['Belum Bayar', 'Menunggu Validasi']);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('no_pendaftaran', 'like', "%{$search}%");
            });
        }

        $pendaftarPending = $query->latest()->get();

        foreach ($pendaftarPending as $data) {
            $tanggalDaftar = $data->created_at ?? now();
            if ($tanggalDaftar->between('2026-01-01', '2026-03-31'))      $gelombang = 'Gelombang 1';
            elseif ($tanggalDaftar->between('2026-04-01', '2026-06-30'))  $gelombang = 'Gelombang 2';
            else                                                           $gelombang = 'Gelombang 3';

            $data->jalur_lengkap = $data->jalur_pendaftaran . ' ' . $gelombang;
            $data->bank_pilihan  = $data->metode_pembayaran ?? 'Belum Dipilih';
            $data->nominal_biaya = $data->nominal_biaya ?? 250000;
        }

        return view('admin.validasi-pembayaran', compact('pendaftarPending'));
    }

    // Alias agar tidak error jika ada route yang masih pakai nama lama
    public function validasiIndex(Request $request)
    {
        return $this->validasiPembayaranIndex($request);
    }

    public function setujuiPembayaran($id)
    {
        $pendaftar = DataPendaftar::findOrFail($id);
        $pendaftar->status_pembayaran = 'Terverifikasi';
        $pendaftar->save();

        ActivityLogger::catat(
            'setujui_pembayaran',
            "Pembayaran pendaftaran {$pendaftar->nama_lengkap} ({$pendaftar->no_pendaftaran}) diverifikasi.",
            ['modul' => 'Pembayaran', 'subjek' => $pendaftar]
        );

        $pesan = "Pemberitahuan Verifikasi Pembayaran\n\n" .
                 "Kepada Yth. {$pendaftar->nama_lengkap},\n\n" .
                 "Pembayaran biaya pendaftaran Anda (No. {$pendaftar->no_pendaftaran}) telah dinyatakan terverifikasi oleh panitia SPMB Universitas Adzkia.\n\n" .
                 "Silakan masuk ke portal dan lanjutkan ke tahap pengisian biodata serta pengunggahan berkas persyaratan:\n" .
                 "http://spmb.adzkia.ac.id/login\n\n" .
                 "Terima kasih.";

        $terkirim = $this->kirimNotifikasiWA($pendaftar->no_whatsapp, $pesan);
        $info = $terkirim ? 'Notifikasi WhatsApp telah dikirim.' : '(Notifikasi WA gagal terkirim, cek koneksi Fonnte).';

        return redirect()->back()->with('success',
            'Pembayaran atas nama ' . $pendaftar->nama_lengkap . ' berhasil diverifikasi. ' . $info);
    }

    public function tolakPembayaran($id)
    {
        $pendaftar = DataPendaftar::findOrFail($id);
        $pendaftar->status_pembayaran = 'Belum Bayar';

        if ($pendaftar->bukti_bayar) {
            \Illuminate\Support\Facades\Storage::delete('public/' . $pendaftar->bukti_bayar);
            $pendaftar->bukti_bayar = null;
        }
        $pendaftar->save();

        ActivityLogger::catat(
            'tolak_pembayaran',
            "Bukti pembayaran {$pendaftar->nama_lengkap} ({$pendaftar->no_pendaftaran}) ditolak, diminta unggah ulang.",
            ['modul' => 'Pembayaran', 'subjek' => $pendaftar]
        );

        $pesan = "Pemberitahuan Verifikasi Pembayaran\n\n" .
                 "Kepada Yth. {$pendaftar->nama_lengkap},\n\n" .
                 "Mohon maaf, bukti pembayaran Anda (No. {$pendaftar->no_pendaftaran}) belum dapat kami verifikasi karena belum memenuhi persyaratan yang ditetapkan.\n\n" .
                 "Silakan masuk kembali ke portal dan unggah ulang bukti pembayaran yang valid dan terbaca dengan jelas:\n" .
                 "http://spmb.adzkia.ac.id/login\n\n" .
                 "Apabila terdapat kendala, silakan menghubungi panitia SPMB. Terima kasih.";

        $terkirim = $this->kirimNotifikasiWA($pendaftar->no_whatsapp, $pesan);
        $info = $terkirim ? 'Notifikasi WA terkirim.' : '(Notifikasi WA gagal terkirim).';

        return redirect()->back()->with('error',
            'Pembayaran ditolak. Pendaftar diminta mengunggah ulang. ' . $info);
    }

    // ==========================================
    // VALIDASI DAFTAR ULANG
    // ==========================================
    public function daftarUlangIndex()
    {
        $pendaftarDaftarUlang = DataPendaftar::whereIn('status_daftar_ulang', [
                                    'Menunggu Validasi', 'Revisi', 'Selesai',
                                ])
                                ->whereNotNull('bukti_daftar_ulang')
                                ->latest()
                                ->get();

        return view('admin.validasi-daftar-ulang', compact('pendaftarDaftarUlang'));
    }

    public function setujuiDaftarUlang($id)
    {
        $pendaftar = DataPendaftar::findOrFail($id);

        if (empty($pendaftar->nim)) {
            $tahun  = date('Y');
            $urutan = str_pad($pendaftar->id, 4, '0', STR_PAD_LEFT);
            $pendaftar->nim = 'ADZ-' . $tahun . '-' . $urutan;
        }

        $pendaftar->status_daftar_ulang = 'Selesai';
        $pendaftar->save();

        ActivityLogger::catat(
            'setujui_daftar_ulang',
            "Daftar ulang {$pendaftar->nama_lengkap} diverifikasi. NIM: {$pendaftar->nim}.",
            ['modul' => 'Daftar Ulang', 'subjek' => $pendaftar]
        );

        $pesan = "Pemberitahuan Resmi SPMB Universitas Adzkia\n\n" .
                 "Kepada Yth. {$pendaftar->nama_lengkap},\n\n" .
                 "Berdasarkan hasil verifikasi, pembayaran daftar ulang dan kelengkapan berkas Anda (No. {$pendaftar->no_pendaftaran}) telah dinyatakan Valid.\n\n" .
                 "Selamat, Anda telah resmi menjadi mahasiswa Universitas Adzkia dengan NIM: {$pendaftar->nim}.\n\n" .
                 "Silakan simpan informasi ini untuk keperluan akademik selanjutnya.\n\n" .
                 "Terima kasih.";

        $terkirim = $this->kirimNotifikasiWA($pendaftar->no_whatsapp, $pesan);
        $info = $terkirim ? 'Notifikasi WA terkirim ke peserta.' : '(Notifikasi WA gagal terkirim).';

        return redirect()->back()->with('success',
            'Pembayaran daftar ulang ' . $pendaftar->nama_lengkap . ' telah diverifikasi. NIM: ' . $pendaftar->nim . '. ' . $info);
    }

    public function revisiDaftarUlang(Request $request, $id)
    {
        $request->validate(['pesan_revisi' => 'required|string']);
        $pendaftar = DataPendaftar::findOrFail($id);
        $pendaftar->update([
            'status_daftar_ulang' => 'Revisi',
            'pesan_revisi'        => $request->pesan_revisi,
        ]);

        ActivityLogger::catat(
            'revisi_daftar_ulang',
            "Berkas daftar ulang {$pendaftar->nama_lengkap} diminta revisi: \"{$request->pesan_revisi}\".",
            ['modul' => 'Daftar Ulang', 'subjek' => $pendaftar]
        );

        $pesan = "Pemberitahuan Perbaikan Berkas Daftar Ulang\n\n" .
                 "Kepada Yth. {$pendaftar->nama_lengkap},\n\n" .
                 "Setelah dilakukan peninjauan, berkas daftar ulang Anda (No. {$pendaftar->no_pendaftaran}) memerlukan perbaikan.\n\n" .
                 "Catatan dari tim verifikasi:\n{$request->pesan_revisi}\n\n" .
                 "Silakan masuk kembali ke portal:\nhttp://spmb.adzkia.ac.id/login\n\nTerima kasih.";

        $terkirim = $this->kirimNotifikasiWA($pendaftar->no_whatsapp, $pesan);
        $info = $terkirim ? 'Notifikasi WA terkirim ke peserta.' : '(Notifikasi WA gagal terkirim).';

        return back()->with('success', 'Pesan revisi berhasil dikirim ke pendaftar. ' . $info);
    }

    // ==========================================
    // PENGUMUMAN KELULUSAN
    // ==========================================
    public function pengumumanIndex(Request $request)
    {
        $query = DataPendaftar::whereIn('status_pendaftaran', ['Selesai', 'Terverifikasi', 'menunggu verifikasi']);

        if ($request->has('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('no_pendaftaran', 'like', '%' . $request->search . '%');
        }

        $pendaftar = $query->orderBy('created_at', 'desc')->paginate(15);

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

        ActivityLogger::catat(
            'tetapkan_kelulusan',
            "Status kelulusan {$pendaftar->nama_lengkap} ({$pendaftar->no_pendaftaran}) ditetapkan: {$request->status_kelulusan}.",
            ['modul' => 'Pengumuman', 'subjek' => $pendaftar]
        );

        $lulus = in_array($request->status_kelulusan, ['Lulus Pilihan 1', 'Lulus Pilihan 2']);

        if ($lulus) {
            $prodiLulus = $request->status_kelulusan === 'Lulus Pilihan 2'
                ? $pendaftar->pilihan_jurusan_2
                : $pendaftar->pilihan_jurusan_1;

            $pesan = "Pengumuman Hasil Seleksi SPMB Universitas Adzkia\n\n" .
                     "Kepada Yth. {$pendaftar->nama_lengkap},\n\n" .
                     "Berdasarkan hasil seleksi SPMB Universitas Adzkia, Anda dinyatakan LULUS pada program studi:\n{$prodiLulus}\n\n" .
                     "Silakan masuk ke portal untuk melihat pengumuman resmi dan mengunduh Surat Kelulusan (LoA):\n" .
                     "http://spmb.adzkia.ac.id/login\n\nKami menantikan kehadiran Anda di kampus. Terima kasih.";
        } else {
            $pesan = "Pengumuman Hasil Seleksi SPMB Universitas Adzkia\n\n" .
                     "Kepada Yth. {$pendaftar->nama_lengkap},\n\n" .
                     "Terima kasih atas partisipasi Anda dalam proses seleksi SPMB Universitas Adzkia.\n\n" .
                     "Setelah melalui proses seleksi, kami sampaikan bahwa Anda belum dapat kami terima pada periode pendaftaran ini.\n\n" .
                     "Anda dipersilakan untuk mencoba kembali pada gelombang pendaftaran berikutnya. Terima kasih.";
        }

        $terkirim = $this->kirimNotifikasiWA($pendaftar->no_whatsapp, $pesan);
        $info = $terkirim ? 'Notifikasi WA terkirim ke peserta.' : '(Notifikasi WA gagal terkirim).';

        return redirect()->back()->with('success',
            'Status kelulusan atas nama ' . $pendaftar->nama_lengkap . ' berhasil ditetapkan. ' . $info);
    }

    public function updateKelulusan(Request $request, $id)
    {
        $request->validate([
            'status_kelulusan' => 'required|in:Lulus Pilihan 1,Lulus Pilihan 2,Tidak Lulus'
        ]);

        $pendaftar = DataPendaftar::findOrFail($id);
        $pendaftar->status_kelulusan = $request->status_kelulusan;
        $pendaftar->save();

        ActivityLogger::catat(
            'update_kelulusan',
            "Status kelulusan {$pendaftar->nama_lengkap} diperbarui menjadi: {$request->status_kelulusan}.",
            ['modul' => 'Pengumuman', 'subjek' => $pendaftar]
        );

        return redirect()->back()->with('success', 'Status kelulusan berhasil diperbarui!');
    }

    // ==========================================
    // VALIDASI FORMULIR (VERIFIKATOR BERKAS)
    // ==========================================
    public function formulirIndex(Request $request)
    {
        $query = DataPendaftar::whereIn('status_pendaftaran', [
            'menunggu verifikasi', 'Selesai', 'Revisi'
        ]);

        if ($request->filled('status')) {
            $query->where('status_pendaftaran', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('no_pendaftaran', 'like', "%{$search}%");
            });
        }

        $pendaftars = $query->latest()->get();

        return view('admin.validasi-formulir', compact('pendaftars'));
    }

    public function setujuiFormulir($id)
    {
        $pendaftar = DataPendaftar::findOrFail($id);
        $pendaftar->update(['status_pendaftaran' => 'Selesai']);

        ActivityLogger::catat(
            'setujui_formulir',
            "Formulir & berkas pendaftaran {$pendaftar->nama_lengkap} ({$pendaftar->no_pendaftaran}) diverifikasi.",
            ['modul' => 'Validasi Formulir', 'subjek' => $pendaftar]
        );

        $pesan = "Pemberitahuan Verifikasi Formulir Pendaftaran\n\n" .
                 "Kepada Yth. {$pendaftar->nama_lengkap},\n\n" .
                 "Formulir dan berkas pendaftaran Anda (No. {$pendaftar->no_pendaftaran}) telah dinyatakan lengkap dan telah diverifikasi oleh tim akademik SPMB Universitas Adzkia.\n\n" .
                 "Silakan pantau pengumuman hasil seleksi melalui portal berikut:\nhttp://spmb.adzkia.ac.id/login\n\nTerima kasih.";

        $terkirim = $this->kirimNotifikasiWA($pendaftar->no_whatsapp, $pesan);
        $info = $terkirim ? 'Notifikasi WA terkirim.' : '(Notifikasi WA gagal terkirim).';

        return back()->with('success',
            'Formulir ' . $pendaftar->nama_lengkap . ' berhasil diverifikasi. ' . $info);
    }

    public function revisiFormulir(Request $request, $id)
    {
        $request->validate(['pesan_revisi' => 'required|string']);

        $pendaftar = DataPendaftar::findOrFail($id);
        $pendaftar->update([
            'status_pendaftaran' => 'Revisi',
            'pesan_revisi'       => $request->pesan_revisi,
        ]);

        ActivityLogger::catat(
            'revisi_formulir',
            "Formulir {$pendaftar->nama_lengkap} diminta revisi: \"{$request->pesan_revisi}\".",
            ['modul' => 'Validasi Formulir', 'subjek' => $pendaftar]
        );

        $pesan = "Pemberitahuan Perbaikan Formulir Pendaftaran\n\n" .
                 "Kepada Yth. {$pendaftar->nama_lengkap},\n\n" .
                 "Formulir pendaftaran Anda (No. {$pendaftar->no_pendaftaran}) memerlukan perbaikan sebelum dapat diproses lebih lanjut.\n\n" .
                 "Catatan dari tim verifikasi:\n{$request->pesan_revisi}\n\n" .
                 "Silakan masuk ke portal dan lakukan perbaikan sesuai catatan di atas:\nhttp://spmb.adzkia.ac.id/login\n\nTerima kasih.";

        $terkirim = $this->kirimNotifikasiWA($pendaftar->no_whatsapp, $pesan);
        $info = $terkirim ? 'Notifikasi WA terkirim.' : '(Notifikasi WA gagal terkirim).';

        return back()->with('success',
            'Pesan revisi berhasil dikirim ke ' . $pendaftar->nama_lengkap . '. ' . $info);
    }

    // ==========================================
    // FUNGSI HELPER WA (Fonnte API)
    // ==========================================
    private function kirimNotifikasiWA($nomor, $pesan)
    {
        $token = env('FONNTE_TOKEN', 'oNrEA5wZL2XwgeMtvQwV');
        $url   = 'https://api.fonnte.com/send';

        try {
            $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                ->timeout(5)
                ->withHeaders(['Authorization' => $token])
                ->post($url, ['target' => $nomor, 'message' => $pesan]);

            if ($response->failed()) {
                \Illuminate\Support\Facades\Log::warning('Fonnte gagal mengirim WA Admin', [
                    'nomor'  => $nomor,
                    'status' => $response->status()
                ]);
                return false;
            }
            return true;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Fonnte Error di Admin: ' . $e->getMessage());
            return false;
        }
    }
}