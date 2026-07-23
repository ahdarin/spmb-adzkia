<?php

namespace App\Http\Controllers;

use App\Models\DataPendaftar;
use App\Models\Prodi;
use App\Models\Jalur;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class DashboardUserController extends Controller
{
    // ==========================================================
    // 1. DASHBOARD UTAMA
    // ==========================================================
    public function dashboardUser()
    {
        $pendaftar = DataPendaftar::find(session('pendaftar_id'));

        if (!$pendaftar) {
            return redirect()->route('login')->withErrors(['error' => 'Silakan login kembali.']);
        }

        $berita = \App\Models\Berita::latest()->take(2)->get();
        $faqs   = \App\Models\Faq::where('kategori', 'Dashboard User')->get();

        return view('user.dashboard-user', compact('pendaftar', 'berita', 'faqs'));
    }

    // ==========================================================
    // 2. FORMULIR PENDAFTARAN AWAL (STEP 1)
    // ==========================================================
    public function formulirIndex()
    {
        $pendaftar = DataPendaftar::find(session('pendaftar_id'));
        if (!$pendaftar) {
            return redirect('/login')->with('error', 'Sesi Anda telah habis, silakan login kembali.');
        }

        $isLocked = !in_array($pendaftar->status_pendaftaran, ['Draft', 'Revisi']);
        $prodis   = Prodi::all();

        $jalurs = Jalur::where('is_active', true)
            ->orderBy('nama_jalur')
            ->get();

        $jalursJson = $jalurs->map(function ($j) {
            return [
                'id'                   => $j->id,
                'nama'                 => $j->nama_jalur,
                'is_free_registration' => (bool) $j->is_free_registration,
                'has_exam'             => (bool) $j->has_exam,
                'dokumen_syarat'       => is_array($j->dokumen_syarat)
                    ? $j->dokumen_syarat
                    : (json_decode($j->dokumen_syarat, true) ?? []),
            ];
        })->values()->toJson();

        return view('user.formulir', compact('pendaftar', 'prodis', 'jalurs', 'jalursJson', 'isLocked'));
    }

    // ==========================================================
    // 3. SIMPAN PENDAFTARAN AWAL
    // ==========================================================
    public function simpanPendaftaran(Request $request)
    {
        $pendaftarId = session('pendaftar_id');
        $pendaftar   = DataPendaftar::findOrFail($pendaftarId);

        if (!in_array($pendaftar->status_pendaftaran, ['Draft', 'Revisi'])) {
            return redirect()->route('konfirmasi-data', $pendaftar->id)
                ->with('error', 'Data Anda telah terkunci karena sudah berada pada tahap ' . $pendaftar->status_pendaftaran . '.');
        }

        $request->validate([
            'jalur_id'          => 'required|exists:jalurs,id',
            'nama_lengkap'      => 'required|string|max:255',
            'nik'               => 'required|string|max:20',
            'gender'            => 'required|in:Laki-laki,Perempuan',
            'agama'             => 'required|string|max:50',
            'tempat_lahir'      => 'nullable|string|max:100',
            'tanggal_lahir'     => 'required|date',
            'email'             => 'nullable|email|max:255',
            'no_whatsapp'       => 'required|string|max:20',
            'provinsi'          => 'required|string',
            'kota_kabupaten'    => 'required|string',
            'alamat_rumah'      => 'required|string',
            'sekolah_asal'      => 'required|string|max:255',
            'npsn_sekolah'      => 'nullable|digits_between:8,10',
            'jurusan_sma'       => 'nullable|string|max:100',
            'tahun_lulus'       => 'nullable|digits:4',
            'nilai_akhir'       => 'required|numeric|min:0|max:100',
            'pilihan_jurusan_1' => 'required|string',
            'pilihan_jurusan_2' => 'required|string|different:pilihan_jurusan_1',
        ], [
            'npsn_sekolah.digits_between' => 'NPSN harus 8-10 digit angka.',
        ]);

        // ── Resolusi data master Sekolah dari NPSN (sebelum transaksi upload
        //    file dimulai, supaya kalau NPSN bentrok kita gagal cepat tanpa
        //    sempat memindahkan file apa pun). ─────────────────────────────
        $resolved = $this->resolveSekolah($request);
        if ($resolved['error']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['npsn_sekolah' => $resolved['error']]);
        }
        $sekolahId = $resolved['sekolah_id'];

        $jalur = Jalur::findOrFail($request->jalur_id);

        $dokumenSyarat = is_array($jalur->dokumen_syarat)
            ? $jalur->dokumen_syarat
            : (json_decode($jalur->dokumen_syarat, true) ?? []);

        $berkasLama  = json_decode($pendaftar->berkas_dokumen ?? '{}', true) ?? [];
        $uploadRules = [];

        foreach ($dokumenSyarat as $dokumen) {
            $fieldName           = 'doc_' . $this->slugifyDokumen($dokumen);
            $sudahAda            = !empty($berkasLama[$dokumen]);
            $uploadRules[$fieldName] = ($sudahAda ? 'nullable' : 'required')
                . '|file|mimes:jpg,jpeg,png,pdf|max:2048';
        }

        if (!empty($uploadRules)) {
            $request->validate($uploadRules, $this->pesanValidasiDokumen($dokumenSyarat));
        }

        DB::beginTransaction();

        try {
            $berkasUpdate = $berkasLama;

            foreach ($dokumenSyarat as $dokumen) {
                $fieldName = 'doc_' . $this->slugifyDokumen($dokumen);

                if ($request->hasFile($fieldName) && $request->file($fieldName)->isValid()) {
                    $file     = $request->file($fieldName);
                    $folder   = 'uploads/dokumen/' . $pendaftar->id;
                    $namaFile = $this->slugifyDokumen($dokumen) . '_' . time() . '.' . $file->getClientOriginalExtension();

                    $file->move(public_path($folder), $namaFile);
                    $berkasUpdate[$dokumen] = $folder . '/' . $namaFile;
                }
            }

            $statusPembayaran = $jalur->is_free_registration ? 'Valid' : 'Belum Bayar';

            $pendaftar->update([
                'jalur_id'          => $jalur->id,
                'nama_lengkap'      => $request->nama_lengkap,
                'nik'               => $request->nik,
                'gender'            => $request->gender,
                'agama'             => $request->agama,
                'tempat_lahir'      => $request->tempat_lahir,
                'tanggal_lahir'     => $request->tanggal_lahir,
                'email'             => $request->email,
                'no_whatsapp'       => $request->no_whatsapp,
                'provinsi'          => $request->provinsi,
                'kota_kabupaten'    => $request->kota_kabupaten,
                'alamat_rumah'      => $request->alamat_rumah,
                'sekolah_asal'      => $request->sekolah_asal,
                'sekolah_id'        => $sekolahId,
                'npsn_sekolah'      => $request->npsn_sekolah,
                'jurusan_sma'       => $request->jurusan_sma,
                'tahun_lulus'       => $request->tahun_lulus,
                'nilai_akhir'       => $request->nilai_akhir,
                'pilihan_jurusan_1' => $request->pilihan_jurusan_1,
                'pilihan_jurusan_2' => $request->pilihan_jurusan_2,
                'berkas_dokumen'    => json_encode($berkasUpdate, JSON_UNESCAPED_UNICODE),
                'status_pembayaran' => $statusPembayaran,
            ]);

            DB::commit();

            if ($jalur->is_free_registration) {
                return redirect()->route('konfirmasi-data', $pendaftar->id)
                    ->with('success', 'Pendaftaran berhasil! Jalur ' . $jalur->nama_jalur . ' tidak memerlukan pembayaran.');
            }

            return redirect()->route('pembayaran.index')
                ->with('success', 'Biodata tersimpan. Silakan lanjutkan pembayaran untuk jalur ' . $jalur->nama_jalur . '.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal proses pendaftaran awal: ' . $e->getMessage(), [
                'pendaftar_id' => $pendaftarId,
                'trace'        => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.')
                ->withInput();
        }
    }

    // ==========================================================
    // HELPER: Resolusi data master Sekolah dari input NPSN + nama sekolah
    // ==========================================================
    /**
     * Mencari atau membuat data master Sekolah berdasarkan NPSN yang diisi
     * mahasiswa di form pendaftaran.
     *
     * - NPSN kosong          -> tidak masalah, sekolah_id null (admin lengkapi manual).
     * - NPSN baru            -> otomatis dibuat sebagai data master baru.
     * - NPSN sudah ada & nama cocok (mirip)   -> dipakai (link ke record itu).
     * - NPSN sudah ada TAPI nama beda jauh    -> ditolak (indikasi typo NPSN,
     *   supaya tidak ada 2 sekolah berbeda numpang di 1 NPSN yang sama).
     *
     * @return array{sekolah_id: int|null, error: string|null}
     */
    private function resolveSekolah(Request $request, string $namaField = 'sekolah_asal', string $npsnField = 'npsn_sekolah'): array
    {
        if (!$request->filled($npsnField)) {
            return ['sekolah_id' => null, 'error' => null];
        }

        $npsn      = trim($request->input($npsnField));
        $namaInput = trim($request->input($namaField, ''));

        $existing = Sekolah::where('npsn', $npsn)->first();

        if ($existing) {
            // Bandingkan nama secara longgar (case-insensitive, toleran typo kecil)
            similar_text(
                $this->normalisasiNamaSekolah($existing->nama_sekolah),
                $this->normalisasiNamaSekolah($namaInput),
                $persen
            );

            if ($persen < 45) {
                return [
                    'sekolah_id' => null,
                    'error' => "NPSN {$npsn} sudah terdaftar atas nama \"{$existing->nama_sekolah}\" di database, "
                             . "tapi nama sekolah yang Anda isi (\"{$namaInput}\") terlihat berbeda. "
                             . "Mohon periksa kembali NPSN sekolah Anda — kemungkinan salah ketik.",
                ];
            }

            return ['sekolah_id' => $existing->id, 'error' => null];
        }

        $baru = Sekolah::create([
            'npsn'         => $npsn,
            'nama_sekolah' => $namaInput,
            'kota'         => $request->input('kota_kabupaten'),
            'provinsi'     => $request->input('provinsi'),
        ]);

        return ['sekolah_id' => $baru->id, 'error' => null];
    }

    /** Normalisasi nama sekolah supaya perbandingan "mirip" lebih adil. */
    private function normalisasiNamaSekolah(string $nama): string
    {
        $nama = strtolower($nama);
        $nama = str_replace(['negeri', 'swasta', '.', ','], '', $nama);
        $nama = preg_replace('/\s+/', ' ', $nama);
        return trim($nama);
    }

    // ==========================================================
    // HELPER: Slugify & Pesan Validasi Dokumen
    // ==========================================================
    private function slugifyDokumen(string $nama): string
    {
        return preg_replace('/[^a-z0-9]+/', '_', strtolower($nama));
    }

    private function pesanValidasiDokumen(array $dokumenSyarat): array
    {
        $pesan = [];
        foreach ($dokumenSyarat as $dokumen) {
            $field = 'doc_' . $this->slugifyDokumen($dokumen);
            $pesan["{$field}.required"] = "File \"{$dokumen}\" wajib diunggah.";
            $pesan["{$field}.mimes"]    = "Format file \"{$dokumen}\" harus JPG, PNG, atau PDF.";
            $pesan["{$field}.max"]      = "Ukuran file \"{$dokumen}\" maksimal 2MB.";
        }
        return $pesan;
    }

    // ==========================================================
    // 4. PEMBAYARAN MANUAL (STEP 2 untuk jalur berbayar)
    // ==========================================================
    public function pembayaranIndex()
    {
        $pendaftar = DataPendaftar::find(session('pendaftar_id'));
        if (!$pendaftar) return redirect('/login')->with('error', 'Sesi Anda telah habis.');

        return view('user.pembayaran', compact('pendaftar'));
    }

    public function prosesUploadBukti(Request $request)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'bukti_pembayaran.required' => 'File bukti pembayaran wajib diisi.',
            'bukti_pembayaran.mimes'    => 'Format file harus JPG, PNG, atau PDF.',
            'bukti_pembayaran.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        $pendaftar = DataPendaftar::find(session('pendaftar_id'));

        if ($request->filled('metode_pembayaran')) {
            $pendaftar->metode_pembayaran = $request->metode_pembayaran;
        }

        if ($request->hasFile('bukti_pembayaran') && $request->file('bukti_pembayaran')->isValid()) {
            $file     = $request->file('bukti_pembayaran');
            $filename = $pendaftar->id . '_' . time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/bukti_bayar'), $filename);
            $pendaftar->bukti_pembayaran  = 'uploads/bukti_bayar/' . $filename;
            $pendaftar->status_pembayaran = 'Menunggu Validasi';
        }

        $pendaftar->save();
        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Harap tunggu proses validasi oleh Admin.');
    }

    // ==========================================================
    // 5. BIODATA LANJUTAN
    //    Route: GET /formulir-biodata → pendaftaran.biodata
    // ==========================================================
    public function biodataIndex()
    {
        $pendaftar = DataPendaftar::find(session('pendaftar_id'));

        if (!$pendaftar) {
            return redirect()->route('login')
                ->with('error', 'Sesi Anda telah habis, silakan login kembali.');
        }

        $prodis = Prodi::all();
        $jalurs = Jalur::where('is_active', true)->orderBy('nama_jalur')->get();

        // ── Auto-resolve jalur_id dari string jalur_pendaftaran ──────────────
        if (empty($pendaftar->jalur_id) && !empty($pendaftar->jalur_pendaftaran)) {
            $namaJalurBersih = trim(explode(' - ', $pendaftar->jalur_pendaftaran)[0]);

            $jalurMatch = $jalurs->first(function ($j) use ($namaJalurBersih) {
                return strtolower($j->nama_jalur) === strtolower($namaJalurBersih)
                    || strtolower($j->kode_nim)   === strtolower($namaJalurBersih);
            });

            if ($jalurMatch) {
                if (Schema::hasColumn('data_pendaftars', 'jalur_id')) {
                    $pendaftar->jalur_id = $jalurMatch->id;
                    $pendaftar->saveQuietly();
                } else {
                    $pendaftar->setAttribute('jalur_id', $jalurMatch->id);
                }
            }
        }

        $jalursJson = $jalurs->map(function ($j) {
            return [
                'id'                   => $j->id,
                'nama'                 => $j->nama_jalur,
                'is_free_registration' => (bool) $j->is_free_registration,
                'has_exam'             => (bool) $j->has_exam,
                'dokumen_syarat'       => is_array($j->dokumen_syarat)
                    ? $j->dokumen_syarat
                    : (json_decode($j->dokumen_syarat, true) ?? []),
            ];
        })->values()->toJson();

        $isLocked = !in_array($pendaftar->status_pendaftaran, ['Draft', 'Revisi']);

        return view('user.formulir', compact('pendaftar', 'prodis', 'jalurs', 'jalursJson', 'isLocked'));
    }

    public function simpanBiodata(Request $request)
    {
        $pendaftar = DataPendaftar::find(session('pendaftar_id'));

        if (!$pendaftar) {
            return redirect()->route('login')
                ->with('error', 'Sesi Anda telah habis, silakan login kembali.');
        }

        $validated = $request->validate([
            'tempat_lahir'    => 'required|string|max:100',
            'tanggal_lahir'   => 'required|date',
            'gender'          => 'required|in:Laki-laki,Perempuan',
            'agama'           => 'required|string|max:50',
            'no_whatsapp'     => 'required|string|max:20',
            'sekolah_asal'    => 'required|string|max:255',
            'npsn_sekolah'    => 'nullable|digits_between:8,10',
            'jurusan_sma'     => 'nullable|string|max:255',
            'tahun_lulus'     => 'required|digits:4|integer',
            'alamat_rumah'    => 'required|string',
        ], [
            'npsn_sekolah.digits_between' => 'NPSN harus 8-10 digit angka.',
        ]);

        $resolved = $this->resolveSekolah($request);
        if ($resolved['error']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['npsn_sekolah' => $resolved['error']]);
        }

        $validated['sekolah_id'] = $resolved['sekolah_id'];

        $pendaftar->update($validated);

        return redirect()->route('konfirmasi-data', $pendaftar->id)
            ->with('success', 'Biodata berhasil disimpan!');
    }

    public function editBiodata()
    {
        $pendaftar = DataPendaftar::find(session('pendaftar_id'));

        if (!$pendaftar) {
            return redirect()->route('login')
                ->with('error', 'Sesi Anda telah habis, silakan login kembali.');
        }

        return view('user.edit-biodata', compact('pendaftar'));
    }

    public function update(Request $request, $id)
    {
        $pendaftar = DataPendaftar::findOrFail($id);

        if ($pendaftar->id !== (int) session('pendaftar_id')) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'tempat_lahir'    => 'required|string|max:100',
            'tanggal_lahir'   => 'required|date',
            'gender'          => 'required|in:Laki-laki,Perempuan',
            'agama'           => 'required|string|max:50',
            'no_whatsapp'     => 'required|string|max:20',
            'sekolah_asal'    => 'required|string|max:255',
            'npsn_sekolah'    => 'nullable|digits_between:8,10',
            'jurusan_sma'     => 'nullable|string|max:255',
            'tahun_lulus'     => 'required|digits:4|integer',
            'alamat_rumah'    => 'required|string',
        ], [
            'npsn_sekolah.digits_between' => 'NPSN harus 8-10 digit angka.',
        ]);

        $resolved = $this->resolveSekolah($request);
        if ($resolved['error']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['npsn_sekolah' => $resolved['error']]);
        }

        $validated['sekolah_id'] = $resolved['sekolah_id'];

        $pendaftar->update($validated);

        return redirect()->route('konfirmasi-data', $pendaftar->id)
            ->with('success', 'Biodata berhasil diperbarui!');
    }

    // ==========================================================
    // 7. KONFIRMASI DATA
    // ==========================================================
    public function tampilkanKonfirmasi($id)
    {
        if ($id != session('pendaftar_id')) {
            abort(403, 'Akses Ditolak. Anda tidak bisa melihat data orang lain.');
        }

        $pendaftar = DataPendaftar::findOrFail($id);

        if (empty($pendaftar->nama_lengkap) || empty($pendaftar->nik)) {
            return redirect()->route('pendaftaran.formulir')
                ->with('error', 'Silakan lengkapi formulir pendaftaran terlebih dahulu.');
        }

        return view('user.konfirmasi-data', compact('pendaftar'));
    }

    public function prosesKonfirmasi($id)
    {
        if ($id != session('pendaftar_id')) {
            abort(403, 'Akses Ditolak.');
        }

        $pendaftar = DataPendaftar::findOrFail($id);
        $pendaftar->status_pendaftaran = 'menunggu verifikasi';
        $pendaftar->save();

        return redirect()->route('pendaftaran.validasiakhir', ['id' => $id]);
    }

    // ==========================================================
    // 8. VALIDASI AKHIR & HASIL
    // ==========================================================
    public function tampilkanValidasiAkhir($id)
    {
        if ($id != session('pendaftar_id')) {
            abort(403, 'Akses Ditolak.');
        }

        $pendaftar = DataPendaftar::findOrFail($id);
        return view('user.validasi-akhir', compact('pendaftar'));
    }

    public function tampilkanSukses()
    {
        $pendaftar = DataPendaftar::find(session('pendaftar_id'));
        if (!$pendaftar) return redirect()->route('dashboard')->with('error', 'Data tidak ditemukan.');
        return view('user.sukses', compact('pendaftar'));
    }

    // ==========================================================
    // 9. CETAK LoA
    // ==========================================================
    public function cetakLoA()
    {
        $pendaftar = DataPendaftar::find(session('pendaftar_id'));

        if (!$pendaftar || strpos($pendaftar->status_kelulusan, 'Lulus') === false || $pendaftar->status_kelulusan == 'Tidak Lulus') {
            return redirect()->route('dashboard.user')->with('error', 'Surat Keterangan Lulus belum tersedia.');
        }

        return view('user.cetak-loa', compact('pendaftar'));
    }

    // ==========================================================
    // 10. PENGUMUMAN HASIL (USER)
    // ==========================================================
    public function tampilkanHasil()
    {
        $pendaftar = DataPendaftar::find(session('pendaftar_id'));
        if (!$pendaftar) return redirect('/login')->with('error', 'Sesi Anda telah habis.');

        return view('user.pengumuman-hasil', compact('pendaftar'));
    }

    // ==========================================================
    // 11. INDEX (alias dashboard)
    // ==========================================================
    public function index()
    {
        return $this->dashboardUser();
    }

    // ==========================================================
    // 12. PROSES PEMBAYARAN (alias upload bukti)
    // ==========================================================
    public function prosesPembayaran(Request $request)
    {
        return $this->prosesUploadBukti($request);
    }
}