<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Faq;
use App\Http\Controllers\AdminPendaftarController;
use App\Http\Controllers\AdminProgramStudiController;
use App\Http\Controllers\AdminBeritaController;
use App\Http\Controllers\AdminTugasController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\AdminMasterController;
use App\Http\Controllers\AdminJalurController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\AdminSekolahController;
use App\Http\Controllers\AdminActivityLogController;
use App\Http\Middleware\CheckRole;

// ══════════════════════════════════════════════════════════════════
// 1. HALAMAN PUBLIK
// ══════════════════════════════════════════════════════════════════

Route::get('/', function () {
    $prodis  = \App\Models\Prodi::take(6)->get();
    $beritas = \App\Models\Berita::where('status', 'Published')->latest()->take(3)->get();
    $faqs    = \App\Models\Faq::where('kategori', 'Dashboard Utama')->take(5)->get();
    return view('dashboard', compact('prodis', 'beritas', 'faqs'));
});

Route::get('/program-studi', function () {
    $prodis         = \App\Models\Prodi::all();
    $gelombangAktif = \App\Models\Gelombang::where('is_active', true)->latest()->first();
    return view('prodi', compact('prodis', 'gelombangAktif'));
});

Route::get('/berita', function () {
    $beritas = \App\Models\Berita::where('status', 'Published')->latest()->get();
    return view('berita', compact('beritas'));
});

Route::get('/berita/{slug}', function ($slug) {
    $berita = \App\Models\Berita::where('slug', $slug)->firstOrFail();
    return view('berita-detail', compact('berita'));
});

// Sistem Rekomendasi AI
Route::prefix('rekomendasi')->name('rekomendasi.')->group(function () {
    Route::get('/mulai',      [RekomendasiController::class, 'start'])->name('start');
    Route::post('/mulai',     [RekomendasiController::class, 'startSubmit'])->name('start.submit');
    Route::get('/kuesioner',  [RekomendasiController::class, 'kuesioner'])->name('kuesioner');
    Route::post('/kuesioner', [RekomendasiController::class, 'kuesionerSubmit'])->name('kuesioner.submit');
    Route::get('/loading',    [RekomendasiController::class, 'loading'])->name('loading');
    Route::post('/proses',    [RekomendasiController::class, 'prosesAIAjax'])->name('proses');
    Route::get('/hasil',      [RekomendasiController::class, 'hasil'])->name('hasil');
});

// Chatbot
Route::post('/api/chat-ai', [ChatbotController::class, 'chat'])->name('rekomendasi.chat.ai');

// API Sekolah — autocomplete user (publik, tanpa auth)
Route::get('/api/sekolah/search', [SekolahController::class, 'search'])->name('api.sekolah.search');
Route::post('/api/sekolah/simpan', [SekolahController::class, 'simpanDariApi'])->name('api.sekolah.simpan');

// Serve file upload
Route::get('/uploads/{folder}/{filename}', function ($folder, $filename) {
    $allowedFolders = ['bukti_pembayaran', 'bukti_daftar_ulang', 'biodata', 'dokumen'];
    if (!in_array($folder, $allowedFolders)) abort(404);
    $path = public_path("uploads/{$folder}/{$filename}");
    if (!file_exists($path)) abort(404, 'File tidak ditemukan.');
    return response()->file($path, ['Content-Type' => mime_content_type($path)]);
})->middleware([CheckRole::class . ':user,admin,super_admin'])->name('uploads.file');


// ══════════════════════════════════════════════════════════════════
// 2. GUEST AREA
// ══════════════════════════════════════════════════════════════════

Route::middleware('guest')->group(function () {
    Route::get('/login',       fn() => view('login'))->name('login');
    Route::get('/login-admin', fn() => view('login-admin'));
    Route::post('/login-proses', [AuthController::class, 'authenticate'])->name('login.post');
    Route::get('/register',    [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register',   [RegisterController::class, 'storeRegister'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ══════════════════════════════════════════════════════════════════
// 3. AREA PENDAFTAR
// ══════════════════════════════════════════════════════════════════

Route::middleware([CheckRole::class . ':user'])->group(function () {

    Route::get('/dashboard',      [DashboardUserController::class, 'index'])->name('dashboard');
    Route::get('/dashboard-user', [DashboardUserController::class, 'dashboardUser'])->name('dashboard.user');

    // Pembayaran
    Route::get('/pembayaran',            [DashboardUserController::class, 'pembayaranIndex'])->name('pembayaran.index');
    Route::post('/pembayaran/proses',    [DashboardUserController::class, 'prosesPembayaran'])->name('simpan.pembayaran');
    Route::post('/upload-bukti',         [DashboardUserController::class, 'prosesUploadBukti'])->name('user.upload-bukti');

    // Biodata
    Route::get('/formulir-biodata',      [DashboardUserController::class, 'biodataIndex'])->name('pendaftaran.biodata');
    Route::post('/simpan-biodata',       [DashboardUserController::class, 'simpanBiodata'])->name('simpan-biodata');
    Route::get('/edit-biodata',          [DashboardUserController::class, 'editBiodata'])->name('edit-biodata');
    Route::put('/update-biodata/{id}',   [DashboardUserController::class, 'update'])->name('update-biodata');

    // Konfirmasi & Finalisasi
    Route::get('/konfirmasi-data/{id}',  [DashboardUserController::class, 'tampilkanKonfirmasi'])->name('konfirmasi-data');
    Route::post('/proses-konfirmasi/{id}',[DashboardUserController::class, 'prosesKonfirmasi'])->name('proses.konfirmasi');
    Route::get('/validasi-akhir/{id}',   [DashboardUserController::class, 'tampilkanValidasiAkhir'])->name('pendaftaran.validasiakhir');
    Route::get('/sukses',                [DashboardUserController::class, 'tampilkanSukses'])->name('pendaftaran.sukses');

    // Pengumuman & Cetak LoA
    Route::get('/pengumuman-hasil', [DashboardUserController::class, 'tampilkanHasil'])->name('pengumuman.hasil');
    Route::get('/cetak-loa',        [DashboardUserController::class, 'cetakLoA'])->name('cetak.loa');

    // Daftar Ulang
    Route::prefix('daftar-ulang')->name('daftar-ulang.')->group(function () {
        Route::get('/data-ortu',   [\App\Http\Controllers\DaftarUlangController::class, 'dataOrtuIndex'])->name('data-ortu');
        Route::post('/data-ortu',  [\App\Http\Controllers\DaftarUlangController::class, 'simpanDataOrtu'])->name('simpan-ortu');
        Route::get('/pembayaran',  [\App\Http\Controllers\DaftarUlangController::class, 'pembayaranIndex'])->name('pembayaran');
        Route::post('/pembayaran', [\App\Http\Controllers\DaftarUlangController::class, 'prosesUploadBukti'])->name('proses-bukti');
    });
});


// ══════════════════════════════════════════════════════════════════
// 4. AREA ADMIN
// ══════════════════════════════════════════════════════════════════

Route::middleware([CheckRole::class . ':admin,super_admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {

    // Dashboard & Pendaftar
    Route::get('/',             [AdminPendaftarController::class, 'dashboard'])->name('dashboard');
    Route::get('/pendaftar',    [AdminPendaftarController::class, 'index'])->name('pendaftar.index');
    Route::get('/export-pendaftar', [AdminPendaftarController::class, 'exportCsv'])->name('export.csv');

    // Pengumuman Kelulusan
    Route::get('/pengumuman',                      [AdminPendaftarController::class, 'pengumumanIndex'])->name('pengumuman');
    Route::post('/pengumuman/tetapkan/{id}',       [AdminPendaftarController::class, 'tetapkanKelulusan'])->name('pengumuman.tetapkan');

    // Validasi Pembayaran
    Route::get('/validasi-pembayaran',             [AdminPendaftarController::class, 'validasiPembayaranIndex'])->name('pembayaran');
    Route::post('/setujui-pembayaran/{id}',        [AdminPendaftarController::class, 'setujuiPembayaran'])->name('setujui.pembayaran');
    Route::post('/tolak-pembayaran/{id}',          [AdminPendaftarController::class, 'tolakPembayaran'])->name('tolak.pembayaran');

    // Validasi Daftar Ulang
    Route::get('/validasi-daftar-ulang',           [AdminPendaftarController::class, 'daftarUlangIndex'])->name('validasi.daftarulang');
    Route::post('/setujui-daftar-ulang/{id}',      [AdminPendaftarController::class, 'setujuiDaftarUlang'])->name('setujui-daftar-ulang');
    Route::post('/revisi-daftar-ulang/{id}',       [AdminPendaftarController::class, 'revisiDaftarUlang'])->name('revisi-daftar-ulang');

    // Validasi Formulir
    Route::get('/validasi-formulir',               [AdminPendaftarController::class, 'formulirIndex'])->name('validasi.formulir');
    Route::post('/setujui-formulir/{id}',          [AdminPendaftarController::class, 'setujuiFormulir'])->name('setujui-formulir');
    Route::post('/revisi-formulir/{id}',           [AdminPendaftarController::class, 'revisiFormulir'])->name('revisi-formulir');

    // Berita
    Route::get('/berita',                          [AdminBeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita/create',                   [AdminBeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita/store',                   [AdminBeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{id}/edit',                [AdminBeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{id}',                     [AdminBeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{id}',                  [AdminBeritaController::class, 'destroy'])->name('berita.destroy');

    // FAQ
    Route::get('/faq', function () {
        $faqs = Faq::latest()->get();
        return view('admin.faq', compact('faqs'));
    })->name('faq');

    Route::post('/faq', function (Request $request) {
        $request->validate(['pertanyaan' => 'required', 'jawaban' => 'required', 'kategori' => 'required']);
        Faq::create($request->all());
        return back()->with('success', 'FAQ berhasil ditambahkan!');
    });

    Route::put('/faq/{id}', function (Request $request, $id) {
        Faq::findOrFail($id)->update($request->all());
        return back()->with('success', 'FAQ berhasil diperbarui!');
    });

    Route::delete('/faq/{id}', function ($id) {
        Faq::findOrFail($id)->delete();
        return back()->with('success', 'FAQ berhasil dihapus!');
    });


    // ── EKSKLUSIF SUPER ADMIN ──────────────────────────────────────

    Route::middleware([CheckRole::class . ':super_admin'])->group(function () {

        // Program Studi
        Route::get('/prodi',           [AdminProgramStudiController::class, 'index'])->name('prodi.index');
        Route::post('/prodi',          [AdminProgramStudiController::class, 'store'])->name('prodi.store');
        Route::put('/prodi/{id}',      [AdminProgramStudiController::class, 'update'])->name('prodi.update');
        Route::delete('/prodi/{id}',   [AdminProgramStudiController::class, 'destroy'])->name('prodi.destroy');

        // Manajemen Divisi
        Route::resource('tugas', AdminTugasController::class)->except(['create', 'show', 'edit']);

        // ── Settings ────────────────────────────────────────────────
        Route::get('/settings',           [AdminSettingController::class, 'index'])->name('settings');
        Route::post('/settings/update',   [AdminSettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/password', [AdminSettingController::class, 'updatePassword'])->name('settings.password');

        // Gelombang (dikelola dari tab Settings)
        Route::post('/settings/gelombang',        [AdminSettingController::class, 'storeGelombang'])  ->name('settings.gelombang.store');
        Route::put('/settings/gelombang/{id}',    [AdminSettingController::class, 'updateGelombang']) ->name('settings.gelombang.update');
        Route::delete('/settings/gelombang/{id}', [AdminSettingController::class, 'destroyGelombang'])->name('settings.gelombang.destroy');

        Route::get('/activity-log', [AdminActivityLogController::class, 'index'])->name('activity-log');
        // ── Master Data ─────────────────────────────────────────────
        Route::prefix('master')->name('master.')->group(function () {

            // Jalur Pendaftaran → admin.master.jalur.index, dst
            Route::get('/jalur',         [AdminJalurController::class, 'index'])  ->name('jalur.index');
            Route::post('/jalur',        [AdminJalurController::class, 'store'])  ->name('jalur.store');
            Route::put('/jalur/{id}',    [AdminJalurController::class, 'update']) ->name('jalur.update');
            Route::delete('/jalur/{id}', [AdminJalurController::class, 'destroy'])->name('jalur.destroy');

            // Biaya Daftar Ulang → admin.master.biaya-daftar-ulang.index, dst
            Route::get('/biaya-daftar-ulang',         [AdminMasterController::class, 'indexBiayaDaftarUlang'])  ->name('biaya-daftar-ulang.index');
            Route::post('/biaya-daftar-ulang',        [AdminMasterController::class, 'storeBiayaDaftarUlang'])  ->name('biaya-daftar-ulang.store');
            Route::put('/biaya-daftar-ulang/{id}',    [AdminMasterController::class, 'updateBiayaDaftarUlang']) ->name('biaya-daftar-ulang.update');
            Route::delete('/biaya-daftar-ulang/{id}', [AdminMasterController::class, 'destroyBiayaDaftarUlang'])->name('biaya-daftar-ulang.destroy');

            // Sekolah → admin.master.sekolah.index, dst
            Route::get('/sekolah',         [AdminSekolahController::class, 'index'])   ->name('sekolah.index');
            Route::get('/sekolah/cari',    [AdminSekolahController::class, 'cariNpsn'])->name('sekolah.cari');
            Route::post('/sekolah',        [AdminSekolahController::class, 'store'])   ->name('sekolah.store');
            Route::put('/sekolah/{id}',    [AdminSekolahController::class, 'update'])  ->name('sekolah.update');
            Route::delete('/sekolah/{id}', [AdminSekolahController::class, 'destroy']) ->name('sekolah.destroy');

        });

    });

});