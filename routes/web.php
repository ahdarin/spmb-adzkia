<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPendaftarController; 
use App\Http\Controllers\AdminProgramStudiController;
use App\Http\Controllers\AdminBeritaController;
use App\Http\Controllers\AdminTugasController; 
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardUserController;

// ==========================================
// 1. HALAMAN UTAMA & PUBLIK
// ==========================================
Route::get('/', function () { return view('dashboard'); });
Route::get('/program-studi', function () { return view('prodi'); });
Route::get('/berita', function () { return view('berita'); });
Route::get('/login', function () { return view('login'); })->name('login');
Route::get('/login-admin', function () { return view('login-admin'); });

Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'storeRegister'])->name('register.store');
Route::post('/login-proses', [RegisterController::class, 'loginProses'])->name('login.proses');
Route::post('/logout', [RegisterController::class, 'logout'])->name('logout');

// ==========================================
// 2. HALAMAN USER (Tanpa Middleware Auth Bawaan)
// ==========================================
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

// ==========================================
// 3. HALAMAN ADMIN
// ==========================================
Route::get('/admin', [AdminPendaftarController::class, 'dashboard'])->name('admin.dashboard'); 
Route::get('/admin/pendaftar', [AdminPendaftarController::class, 'index'])->name('admin.pendaftar.index');

Route::get('/admin/validasi-pembayaran', [AdminPendaftarController::class, 'validasiPembayaranIndex'])->name('admin.pembayaran');
Route::post('/admin/proses-validasi', [DashboardUserController::class, 'prosesValidasi'])->name('admin.proses.validasi');

Route::get('/admin/validasi-daftar-ulang', [AdminPendaftarController::class, 'daftarUlangIndex'])->name('admin.validasi.daftarulang');
Route::post('/admin/setujui-daftar-ulang/{id}', [AdminPendaftarController::class, 'setujuiDaftarUlang'])->name('admin.setujui-daftar-ulang');

Route::get('/admin/pengumuman', [AdminPendaftarController::class, 'pengumumanIndex'])->name('admin.pengumuman');
Route::post('/admin/update-kelulusan/{id}', [AdminPendaftarController::class, 'updateKelulusan'])->name('admin.update-kelulusan');

Route::get('/admin/faq', function () { return view('admin.faq'); });
Route::get('/admin/settings', function () { return view('admin.settings'); });

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/prodi', [AdminProgramStudiController::class, 'index'])->name('prodi.index');
    Route::post('/prodi', [AdminProgramStudiController::class, 'store'])->name('prodi.store');
    Route::put('/prodi/{id}', [AdminProgramStudiController::class, 'update'])->name('prodi.update');
    Route::delete('/prodi/{id}', [AdminProgramStudiController::class, 'destroy'])->name('prodi.destroy');
    
    Route::get('/berita', [AdminBeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita/create', [AdminBeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita/store', [AdminBeritaController::class, 'store'])->name('berita.store');
    
    Route::get('/tugas', [AdminTugasController::class, 'index'])->name('tugas.index');
    Route::put('/tugas/{id}', [AdminTugasController::class, 'update'])->name('tugas.update');
});