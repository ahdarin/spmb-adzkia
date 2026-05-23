<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPendaftar;
use App\Models\MetodePembayaran; 
use Carbon\Carbon;
use App\Models\Prodi;
use App\Models\Pendaftar;

class DashboardUserController extends Controller
{
    public function index() // <--- Pastikan namanya 'index'
    {
        $pendaftarId = session('pendaftar_id');

        if (!$pendaftarId) {
            return redirect('/login')->withErrors(['error' => 'Silakan login terlebih dahulu!']);
        }

        $mahasiswa = DataPendaftar::with('prodi1')->find($pendaftarId);

        if (!$mahasiswa) {
            return redirect('/login')->withErrors(['error' => 'Data pendaftar tidak ditemukan!']);
        }

        // Ambil data dari table metode_pembayaran
        $semuaMetode = MetodePembayaran::where('is_active', true)->get();

        // Filter perkategori
        $bankTransfer   = $semuaMetode->where('kategori', 'Bank Transfer');
        $virtualAccount = $semuaMetode->where('kategori', 'Virtual Account');
        $eWallet        = $semuaMetode->where('kategori', 'E-Wallet');
        
        $batasWaktu = now()->addDays(1)->format('M d, Y (23:59 \W\I\B)');

        // Kirim kelimanya ke Blade
        return view('user.pembayaran', compact(
            'mahasiswa', 
            'bankTransfer', 
            'virtualAccount', 
            'eWallet', 
            'batasWaktu'
        ));
    }

    public function pembayaranIndex()
    {
        // 1. Data Virtual Account (Dummy untuk sementara agar tidak error)
        $virtualAccount = [
            (object)['nama_provider' => 'MANDIRI', 'nama_bank_lengkap' => 'Bank Mandiri', 'nomor_tujuan' => '1234567890', 'atas_nama' => 'Universitas Adzkia'],
            (object)['nama_provider' => 'BCA', 'nama_bank_lengkap' => 'Bank BCA', 'nomor_tujuan' => '0987654321', 'atas_nama' => 'Universitas Adzkia'],
        ];

        // 2. Data Bank Transfer (INI YANG KAMU CARI - HARUS ADA)
        $bankTransfer = [
            (object)['nama_provider' => 'BCA_MANUAL', 'nama_bank_lengkap' => 'BCA (Transfer Manual)', 'nomor_tujuan' => '0987654321', 'atas_nama' => 'Universitas Adzkia'],
            (object)['nama_provider' => 'BNI_MANUAL', 'nama_bank_lengkap' => 'BNI (Transfer Manual)', 'nomor_tujuan' => '1122334455', 'atas_nama' => 'Universitas Adzkia'],
        ];

        // 3. Batas Waktu
        $batasWaktu = Carbon::now()->addDays(1)->format('d F Y, H:i');

        // 4. KIRIM SEMUA KE VIEW
        return view('user.pembayaran', compact('virtualAccount', 'bankTransfer', 'batasWaktu'));
    }

    // Di DashboardUserController.php (fungsi simpan pembayaran)
public function prosesPembayaran(Request $request)
{
    $pendaftar = \App\Models\DataPendaftar::where('user_id', session('user_id'))->first();
    
    // Simpan metode pembayaran yang dipilih user
    $user->update([
        'metode_pembayaran' => $request->metode_pembayaran, // Misal: Mandiri/BCA
        'nominal_biaya'     => 250000, // Sesuaikan dengan biaya yang ditentukan
        'status_pembayaran' => 'Validasi', // Mengubah status agar muncul di admin
    ]);

    return redirect()->route('dashboard.user')->with('success', 'Pembayaran diproses!');
}

// Pastikan kode ini berada DI DALAM class DashboardUserController
public function prosesValidasi(Request $request)
{
    // 1. Validasi input dari form
    $request->validate([
        'pendaftar_id' => 'required|exists:data_pendaftars,id',
    ]);

    // 2. Cari data berdasarkan ID yang dikirim dari form
    $pendaftar = \App\Models\DataPendaftar::find($request->pendaftar_id);
    
    if ($pendaftar) {
        // 3. Update status
        $pendaftar->update([
            'status_pembayaran' => 'Terverifikasi'
        ]);

        // 4. Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Pembayaran berhasil diverifikasi!');
    }

    // 5. Jika data tidak ditemukan
    return back()->with('error', 'Data tidak ditemukan.');
}

public function dashboardUser()
{
    $pendaftar = \App\Models\DataPendaftar::find(session('pendaftar_id'));

    // Pengecekan Kunci:
    if ($pendaftar->status_pembayaran !== 'Terverifikasi') {
        return redirect()->route('pendaftaran.validasi')
                         ->with('error', 'Mohon tunggu verifikasi pembayaran oleh admin.');
    }

    return view('user.dashboard');
}

public function validasiUser()
{
    // Gunakan 'id' bukan 'user_id' karena database kamu belum punya kolom user_id
    $pendaftar = \App\Models\DataPendaftar::find(session('pendaftar_id'));

    if (!$pendaftar) {
        return redirect()->route('login')->withErrors(['error' => 'Silakan login kembali.']);
    }

    return view('user.validasi-pembayaran', compact('pendaftar'));
}

public function prosesUploadBukti(Request $request)
{
    $request->validate(['bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
    
    $pendaftar = \App\Models\DataPendaftar::find(session('pendaftar_id'));
    
    // Simpan file
    $path = $request->file('bukti_bayar')->store('bukti_pembayaran', 'public');
    
    // Update status agar admin bisa melihatnya di halaman validasi
    $pendaftar->update([
        'bukti_bayar' => $path,
        'status_pembayaran' => 'Menunggu Validasi' // Status ini yang akan dibaca admin
    ]);

    return redirect()->route('pendaftaran.validasi')->with('success', 'Bukti berhasil diunggah!');
}

public function showBiodata()
{
    // Ambil data pendaftar yang login
    $pendaftar = \App\Models\DataPendaftar::find(session('pendaftar_id'));

    // KUNCI: Jika status belum Terverifikasi, tendang balik ke halaman validasi
    if ($pendaftar->status_pembayaran !== 'Terverifikasi') {
        return redirect()->route('pendaftaran.validasi')
                         ->with('error', 'Mohon tunggu verifikasi pembayaran oleh admin sebelum melanjutkan.');
    }

    return view('user.biodata');
}
// DashboardUserController.php

public function biodataIndex()
{
    // 1. Ambil ID dari session yang benar ('pendaftar_id' sesuai kode login kamu)
    $pendaftarId = session('pendaftar_id');

    // 2. Ambil data pendaftar berdasarkan ID tersebut
    $pendaftar = \App\Models\DataPendaftar::find($pendaftarId);

    // 3. Debugging untuk memastikan data terambil
    if (!$pendaftar) {
        dd("Session 'pendaftar_id' tidak ditemukan atau data tidak ada di database. ID Session: " . $pendaftarId);
    }

    // 4. Cek status pembayaran (menggunakan strtolower agar tidak case-sensitive)
    if (strtolower($pendaftar->status_pembayaran) !== 'terverifikasi') {
        return redirect()->route('pembayaran.index')
                         ->with('error', 'Status pembayaran belum terverifikasi.');
    }

    // 5. Jika lolos, ambil data prodi dan tampilkan view
    $prodis = \App\Models\Prodi ::all(); 
    return view('user.formulir', compact('pendaftar', 'prodis')); 
}

public function simpanBiodata(Request $request)
{
    // 1. Validasi
    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'nik'          => 'required|string|max:20',
        'gender'       => 'required',
        'prodi_id'     => 'required',
        'pas_foto'     => 'required|file|image|max:2048',
        'scan_ktp'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'ijazah_skl'   => 'required|file|mimes:pdf,jpg,png|max:2048',
    ]);

    // 2. Proses Upload File
    // Menggunakan store() untuk memindahkan file ke folder di public disk
    $pathFoto   = $request->file('pas_foto')->store('dokumen/foto', 'public');
    $pathKtp    = $request->file('scan_ktp')->store('dokumen/ktp', 'public');
    $pathIjazah = $request->file('ijazah_skl')->store('dokumen/ijazah', 'public');

    // 3. Simpan ke Database
    \App\Models\DataPendaftar::updateOrCreate(
        ['user_id' => auth()->id()],
        [
            'nama_lengkap' => $request->nama_lengkap,
            'nik'          => $request->nik,
            'gender'       => $request->gender,
            'prodi_id'     => $request->prodi_id,
            'pas_foto'     => $pathFoto,
            'scan_ktp'     => $pathKtp,
            'ijazah_skl'   => $pathIjazah,
        ]
    );

    return redirect()->route('pendaftaran.konfirmasi')->with('success', 'Biodata berhasil disimpan!');
}


public function showVerifikasi($id)
{
    $pendaftar = \App\Models\DataPendaftar::findOrFail($id);
    return view('admin.verifikasi', compact('pendaftar'));
}

public function tampilkanKonfirmasi($id)
{
    $pendaftar = \App\Models\DataPendaftar::findOrFail($id);

    if (!$pendaftar) {
        return redirect()->route('formulir-biodata')->with('error', 'Silakan lengkapi biodata terlebih dahulu.');
    }

    // Kirim data ke view
    return view('user.konfirmasi-data', compact('pendaftar'));
}


public function editBiodata()
{
    // Mengambil data berdasarkan user yang sedang login
    $pendaftar = \App\Models\DataPendaftar::where('user_id', auth()->id())->firstOrFail();
    
    return view('user.edit-biodata', compact('pendaftar'));
}


// Contoh di PendaftaranController.php
public function edit()
{
    // Mengambil data pendaftar milik user yang sedang login
    $pendaftar = \App\Models\DataPendaftar::where('user_id', auth()->id())->first();

    // Mengirim variabel $pendaftar ke file formulir-biodata.blade.php
    return view('user.formulir-biodata', compact('pendaftar'));
}


public function update(Request $request, $id)
{
    // Cari data pendaftar berdasarkan ID
    $pendaftar = \App\Models\DataPendaftar::findOrFail($id);

    // Validasi NIK dengan mengecualikan ID pendaftar itu sendiri
    $request->validate([
        'nama_lengkap' => 'required',
        // 'nik' unik di tabel 'data_pendaftars', kolom 'nik', kecuali ID ini
        'nik'          => 'required|unique:data_pendaftars,nik,' . $pendaftar->id, 
    ]);

    // Lanjutkan proses update...
    $pendaftar->update($request->all());

    return redirect()->route('konfirmasi-data', ['id' => $pendaftar->id])
                 ->with('success', 'Data berhasil diperbarui!');
} 

public function tampilkanValidasiAkhir($id)
{
    // Mengambil data pendaftar berdasarkan ID
    $pendaftar = \App\Models\DataPendaftar::findOrFail($id);
    
    return view('user.validasi-akhir', compact('pendaftar'));
}

public function prosesKonfirmasi($id)
{
    // 1. Cari data pendaftar berdasarkan ID
    $pendaftar = \App\Models\DataPendaftar::findOrFail($id);

    // 2. Gunakan kolom 'status_pendaftaran' yang sudah ada di database
    $pendaftar->status_pendaftaran = 'menunggu verifikasi'; 
    $pendaftar->save();

    // 3. Redirect ke halaman validasi akhir
    return redirect()->route('pendaftaran.validasiakhir', ['id' => $id]);
}

public function tampilkanSukses()
{
    // Mengambil data pendaftar yang memiliki 'user_id' sama dengan user yang login
    $pendaftar = \App\Models\DataPendaftar::where('user_id', auth()->id())->first();

    // Jika data tidak ditemukan, arahkan kembali atau tampilkan error
    if (!$pendaftar) {
        return redirect()->route('dashboard')->with('error', 'Data pendaftaran tidak ditemukan.');
    }
    
    return view('user.sukses', compact('pendaftar'));
}
}
