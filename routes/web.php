<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPendaftarController; 
use App\Http\Controllers\AdminProgramStudiController;
use App\Http\Controllers\AdminBeritaController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminTugasController; 

// ==========================================
// 1. HALAMAN UTAMA & PUBLIK
// ==========================================
Route::get('/', function () { return view('dashboard'); });
Route::get('/program-studi', function () { return view('prodi'); });
Route::get('/berita', function () { return view('berita'); });
Route::get('/login', function () { return view('login'); });
Route::get('/login-admin', function () { return view('login-admin'); });


// ==========================================
// 2. HALAMAN USER & PENDAFTARAN
// ==========================================
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/pembayaran', function () { return view('user.pembayaran'); })->name('pembayaran.index');
Route::get('/validasi-pembayaran', function () { return view('user.validasi-pembayaran'); })->name('pembayaran.validasi');
Route::get('/biodata', function () { return view('user.formulir'); })->name('pendaftaran.biodata');
Route::get('/konfirmasi-data', function () { return view('user.konfirmasi-data'); })->name('pendaftaran.konfirmasi');
Route::get('/validasi-akhir', function () { return view('user.validasi-akhir'); })->name('pendaftaran.validasiakhir');
Route::get('/sukses', function () { return view('user.sukses'); })->name('pendaftaran.sukses');

// Fitur Rekomendasi Jurusan
Route::get('/rekomendasi/mulai', function () { return view('user.rekomendasi-start'); }); 
Route::get('/rekomendasi/kuesioner', function () { return view('user.kuesioner'); });
Route::get('/rekomendasi/hasil', function () { return view('user.hasil-rekomendasi'); });


// ==========================================
// 3. HALAMAN ADMIN & DASHBOARD
// ==========================================
// Rute Admin (Tanpa Prefix)
Route::get('/admin', [AdminPendaftarController::class, 'dashboard'])->name('admin.dashboard'); 
Route::get('/admin/pendaftar', [AdminPendaftarController::class, 'index'])->name('admin.pendaftar.index');
Route::get('/admin/validasi-pembayaran', [AdminPendaftarController::class, 'validasiPembayaranIndex'])->name('admin.pembayaran');
Route::get('/admin/validasi-daftar-ulang', [AdminPendaftarController::class, 'daftarUlangIndex'])->name('admin.validasi.daftarulang');
Route::get('/admin/pengumuman', [AdminPendaftarController::class, 'pengumumanIndex'])->name('admin.pengumuman');
Route::post('/admin/update-kelulusan/{id}', [AdminPendaftarController::class, 'updateKelulusan'])->name('admin.update-kelulusan');
Route::get('/admin/faq', function () { return view('admin.faq'); });
Route::get('/admin/settings', function () { return view('admin.settings'); });

// Rute Admin (Dengan Prefix '/admin' dan penamaan 'admin.')
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Kelola Program Studi
    Route::get('/prodi', [AdminProgramStudiController::class, 'index'])->name('prodi.index');
    Route::post('/prodi', [AdminProgramStudiController::class, 'store'])->name('prodi.store');
    Route::put('/prodi/{id}', [AdminProgramStudiController::class, 'update'])->name('prodi.update');
    Route::delete('/prodi/{id}', [AdminProgramStudiController::class, 'destroy'])->name('prodi.destroy');
    
    // Kelola Berita
    Route::get('/berita', [AdminBeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita/create', [AdminBeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita/store', [AdminBeritaController::class, 'store'])->name('berita.store');

    // Manajemen Divisi / Tugas Admin
    Route::get('/tugas', [AdminTugasController::class, 'index'])->name('tugas.index');
    Route::put('/tugas/{id}', [AdminTugasController::class, 'update'])->name('tugas.update');
});