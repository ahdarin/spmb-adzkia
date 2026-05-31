<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Faq;
use App\Http\Controllers\AdminPendaftarController; 
use App\Http\Controllers\AdminProgramStudiController;
use App\Http\Controllers\AdminBeritaController;
use App\Http\Controllers\AdminTugasController; 
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\CheckRole; 

// ==========================================
// 1. HALAMAN UTAMA & PUBLIK
// ==========================================
    Route::get('/', function () { 
    // Menggunakan \App\Models\ secara langsung agar Laravel tidak kebingungan
    $prodis = \App\Models\Prodi::take(3)->get();
    $beritas = \App\Models\Berita::latest()->take(3)->get();
    $faqs = \App\Models\Faq::take(5)->get();
    
    return view('dashboard', compact('prodis', 'beritas', 'faqs')); 
    });

    Route::get('/program-studi', function () { return view('prodi'); });
    Route::get('/berita', function () { return view('berita'); });

    // ==========================================
    // 2. GUEST AREA (Hanya bisa diakses jika BELUM login)
    // ==========================================
    Route::middleware('guest')->group(function () {
    Route::get('/login', function () { return view('login'); })->name('login');
    Route::get('/login-admin', function () { return view('login-admin'); });
    
    Route::post('/login-proses', [AuthController::class, 'authenticate'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'storeRegister'])->name('register.store');
});

// ==========================================
// RUTE LOGOUT (Bebas diakses jika sudah login)
// ==========================================
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ==========================================
// 3. AREA PENDAFTAR (Sistem Keuangan, Biodata, dll)
// ==========================================
Route::middleware([CheckRole::class.':user'])->group(function () {
    
    Route::get('/dashboard', [DashboardUserController::class, 'index'])->name('dashboard');
    Route::get('/dashboard-user', [DashboardUserController::class, 'dashboardUser'])->name('dashboard.user'); 

    // Pembayaran
    Route::get('/pembayaran', [DashboardUserController::class, 'pembayaranIndex'])->name('pembayaran.index');
    Route::post('/pembayaran/proses', [DashboardUserController::class, 'prosesPembayaran'])->name('simpan.pembayaran');
    Route::post('/upload-bukti', [DashboardUserController::class, 'prosesUploadBukti'])->name('user.upload-bukti');
    Route::get('/validasi-pembayaran', [DashboardUserController::class, 'validasiUser'])->name('pendaftaran.validasi');

    // Biodata
    Route::get('/formulir-biodata', [DashboardUserController::class, 'biodataIndex'])->name('pendaftaran.biodata');
    Route::post('/simpan-biodata', [DashboardUserController::class, 'simpanBiodata'])->name('simpan-biodata');
    Route::get('/edit-biodata', [DashboardUserController::class, 'editBiodata'])->name('edit-biodata');
    Route::put('/update-biodata/{id}', [DashboardUserController::class, 'update'])->name('update-biodata');

    // Konfirmasi & Finalisasi
    Route::get('/konfirmasi-data/{id}', [DashboardUserController::class, 'tampilkanKonfirmasi'])->name('konfirmasi-data');
    Route::post('/proses-konfirmasi/{id}', [DashboardUserController::class, 'prosesKonfirmasi'])->name('proses.konfirmasi');
    Route::get('/validasi-akhir/{id}', [DashboardUserController::class, 'tampilkanValidasiAkhir'])->name('pendaftaran.validasiakhir');
    Route::get('/sukses', [DashboardUserController::class, 'tampilkanSukses'])->name('pendaftaran.sukses'); 

    // Rekomendasi
    Route::get('/rekomendasi/mulai', function () { return view('user.rekomendasi-start'); }); 
    Route::get('/rekomendasi/kuesioner', function () { return view('user.kuesioner'); });
    Route::get('/rekomendasi/hasil', function () { return view('user.hasil-rekomendasi'); });

    // Pengumuman Hasil Kelulusan (USER)
    Route::get('/pengumuman-hasil', [DashboardUserController::class, 'tampilkanHasil'])->name('pengumuman.hasil');
    Route::get('/cetak-loa', [DashboardUserController::class, 'cetakLoA'])->name('cetak.loa');
});


// ==========================================
// 4. AREA ADMIN (Sistem Validasi & Pengumuman)
// ==========================================
Route::middleware([CheckRole::class.':admin,super_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/', [AdminPendaftarController::class, 'dashboard'])->name('dashboard'); 
    Route::get('/pendaftar', [AdminPendaftarController::class, 'index'])->name('pendaftar.index');
    Route::get('/export-pendaftar', [AdminPendaftarController::class, 'exportCsv'])->name('export.csv');

// Pengumuman Kelulusan (ADMIN)
    Route::get('/pengumuman', [AdminPendaftarController::class, 'pengumumanIndex'])->name('pengumuman');
    Route::post('/pengumuman/tetapkan/{id}', [AdminPendaftarController::class, 'tetapkanKelulusan'])->name('pengumuman.tetapkan');

    // Pembayaran & Daftar Ulang
    Route::get('/validasi-pembayaran', [AdminPendaftarController::class, 'validasiPembayaranIndex'])->name('pembayaran');
    Route::post('/setujui-pembayaran/{id}', [AdminPendaftarController::class, 'setujuiPembayaran'])->name('setujui.pembayaran');   
    Route::post('/tolak-pembayaran/{id}', [AdminPendaftarController::class, 'tolakPembayaran'])->name('tolak.pembayaran'); 
    Route::get('/validasi-daftar-ulang', [AdminPendaftarController::class, 'daftarUlangIndex'])->name('validasi.daftarulang');
    Route::post('/setujui-daftar-ulang/{id}', [AdminPendaftarController::class, 'setujuiDaftarUlang'])->name('setujui-daftar-ulang');
    Route::post('/revisi-daftar-ulang/{id}', [AdminPendaftarController::class, 'revisiDaftarUlang'])->name('revisi-daftar-ulang');
    
    // Pengumuman & Berita (Informasi Umum)
    Route::get('/berita', [AdminBeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita/create', [AdminBeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita/store', [AdminBeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{id}/edit', [AdminBeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{id}', [AdminBeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{id}', [AdminBeritaController::class, 'destroy'])->name('berita.destroy');

    // ------------------------------------------
    // KELOLA FAQ (PUSAT BANTUAN)
    // ------------------------------------------
    Route::get('/faq', function () { 
        $faqs = Faq::latest()->get(); 
        return view('admin.faq', compact('faqs')); 
    })->name('faq');

    Route::post('/faq', function (Request $request) {
        $request->validate([
            'pertanyaan' => 'required',
            'jawaban' => 'required',
            'kategori' => 'required'
        ]);
        Faq::create($request->all());
        return back()->with('success', 'FAQ berhasil ditambahkan!');
    });

    Route::put('/faq/{id}', function (Request $request, $id) {
        $faq = Faq::findOrFail($id);
        $faq->update($request->all());
        return back()->with('success', 'FAQ berhasil diperbarui!');
    });

    Route::delete('/faq/{id}', function ($id) {
        Faq::findOrFail($id)->delete();
        return back()->with('success', 'FAQ berhasil dihapus!');
    });

    // ------------------------------------------
    // EKSKLUSIF SUPER ADMIN
    // ------------------------------------------
    Route::middleware([CheckRole::class.':super_admin'])->group(function () {
        Route::get('/prodi', [AdminProgramStudiController::class, 'index'])->name('prodi.index');
        Route::post('/prodi', [AdminProgramStudiController::class, 'store'])->name('prodi.store');
        Route::put('/prodi/{id}', [AdminProgramStudiController::class, 'update'])->name('prodi.update');
        Route::delete('/prodi/{id}', [AdminProgramStudiController::class, 'destroy'])->name('prodi.destroy');
        
        Route::resource('tugas', AdminTugasController::class)->except(['create', 'show', 'edit']); 
        
        Route::get('/settings', function () { return view('admin.settings'); })->name('settings');
    });
});