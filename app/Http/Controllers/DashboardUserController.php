<?php

namespace App\Http\Controllers;

use App\Models\DataPendaftar;
use App\Models\Prodi;
use App\Models\Jalur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    //    Pilih Jalur + Biodata Utama + Upload Dokumen Dinamis
    // ==========================================================
    public function formulirIndex()
    {
        $pendaftar = DataPendaftar::find(session('pendaftar_id'));
        if (!$pendaftar) {
            return redirect('/login')->with('error', 'Sesi Anda telah habis, silakan login kembali.');
        }

        // Kalau sudah masuk tahap verifikasi/lulus, tidak boleh ubah jalur/dokumen lagi
        $isLocked = !in_array($pendaftar->status_pendaftaran, ['Draft', 'Revisi']);

        $prodis = Prodi::all();
        $jalurs = Jalur::where('is_active', true)->orderBy('nama')->get();

        // Dikirim ke Alpine.js di view untuk render dokumen dinamis
        $jalursJson = $jalurs->map(function ($j) {
            return [
                'id'                    => $j->id,
                'nama'                  => $j->nama,
                'is_free_registration'  => (bool) $j->is_free_registration,
                'has_exam'              => (bool) $j->has_exam,
                'dokumen_syarat'        => is_array($j->dokumen_syarat)
                    ? $j->dokumen_syarat
                    : (json_decode($j->dokumen_syarat, true) ?? []),
            ];
        })->values()->toJson();

        return view('user.formulir', compact('pendaftar', 'prodis', 'jalurs', 'jalursJson', 'isLocked'));
    }

    /**
     * Proses simpan Pendaftaran Awal.
     * - Simpan biodata utama (TANPA data ortu)
     * - Validasi & upload dokumen dinamis sesuai dokumen_syarat jalur
     * - Jika is_free_registration -> status_pembayaran = 'Valid' (lunas otomatis)
     * - Jika berbayar -> status_pembayaran = 'Belum Bayar', arahkan ke halaman
     *   upload bukti pembayaran manual (pembayaranIndex)
     */
    public function simpanPendaftaran(Request $request)
    {
        $pendaftarId = session('pendaftar_id');
        $pendaftar   = DataPendaftar::findOrFail($pendaftarId);

        if (!in_array($pendaftar->status_pendaftaran, ['Draft', 'Revisi'])) {
            return redirect()->route('konfirmasi-data', $pendaftar->id)
                ->with('error', 'Data Anda telah terkunci karena sudah berada pada tahap ' . $pendaftar->status_pendaftaran . '.');
        }

        // -----------------------------------------------------
        // VALIDASI DASAR (biodata utama saja, TANPA data ortu)
        // -----------------------------------------------------
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
            'jurusan_sma'       => 'nullable|string|max:100',
            'tahun_lulus'       => 'nullable|digits:4',
            'nilai_akhir'       => 'required|numeric|min:0|max:100',
            'pilihan_jurusan_1' => 'required|string',
            'pilihan_jurusan_2' => 'required|string|different:pilihan_jurusan_1',
        ]);

        $jalur = Jalur::findOrFail($request->jalur_id);

        $dokumenSyarat = is_array($jalur->dokumen_syarat)
            ? $jalur->dokumen_syarat
            : (json_decode($jalur->dokumen_syarat, true) ?? []);

        // -----------------------------------------------------
        // VALIDASI UPLOAD DOKUMEN DINAMIS
        // -----------------------------------------------------
        $berkasLama  = json_decode($pendaftar->berkas_dokumen ?? '{}', true) ?? [];
        $uploadRules = [];

        foreach ($dokumenSyarat as $dokumen) {
            $fieldName    = 'doc_' . $this->slugifyDokumen($dokumen);
            $sudahAda     = !empty($berkasLama[$dokumen]);
            $uploadRules[$fieldName] = ($sudahAda ? 'nullable' : 'required')
                . '|file|mimes:jpg,jpeg,png,pdf|max:2048';
        }

        if (!empty($uploadRules)) {
            $request->validate($uploadRules, $this->pesanValidasiDokumen($dokumenSyarat));
        }

        DB::beginTransaction();

        try {
            // -----------------------------------------------------
            // UPLOAD DOKUMEN DINAMIS
            // -----------------------------------------------------
            $berkasUpdate = $berkasLama;

            foreach ($dokumenSyarat as $dokumen) {
                $fieldName = 'doc_' . $this->slugifyDokumen($dokumen);

                if ($request->hasFile($fieldName)) {
                    $file     = $request->file($fieldName);
                    $folder   = 'uploads/dokumen/' . $pendaftar->id;
                    $namaFile = $this->slugifyDokumen($dokumen) . '_' . time() . '.' . $file->getClientOriginalExtension();

                    $file->move(public_path($folder), $namaFile);
                    $berkasUpdate[$dokumen] = $folder . '/' . $namaFile;
                }
            }

            // -----------------------------------------------------
            // TENTUKAN STATUS PEMBAYARAN AWAL
            // Gratis (KIP/BAU) -> langsung Valid
            // Berbayar -> Belum Bayar, nanti diupload manual di step Pembayaran
            // -----------------------------------------------------
            $statusPembayaran = $jalur->is_free_registration ? 'Valid' : 'Belum Bayar';

            // -----------------------------------------------------
            // SIMPAN BIODATA UTAMA + JALUR
            // (field diambil eksplisit, TIDAK pakai update massal $request->all()
            //  supaya data ortu / field lain tidak ikut ke sini)
            // -----------------------------------------------------
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
                'jurusan_sma'       => $request->jurusan_sma,
                'tahun_lulus'       => $request->tahun_lulus,
                'nilai_akhir'       => $request->nilai_akhir,
                'pilihan_jurusan_1' => $request->pilihan_jurusan_1,
                'pilihan_jurusan_2' => $request->pilihan_jurusan_2,
                'berkas_dokumen'    => json_encode($berkasUpdate, JSON_UNESCAPED_UNICODE),
                'status_pembayaran' => $statusPembayaran,
            ]);

            DB::commit();

            // -----------------------------------------------------
            // REDIRECT SESUAI JENIS JALUR
            // -----------------------------------------------------
            if ($jalur->is_free_registration) {
                // Gratis -> langsung ke konfirmasi data, skip pembayaran
                return redirect()->route('konfirmasi-data', $pendaftar->id)
                    ->with('success', 'Pendaftaran berhasil! Jalur ' . $jalur->nama . ' tidak memerlukan pembayaran.');
            }

            // Berbayar -> arahkan ke halaman upload bukti pembayaran manual
            return redirect()->route('pembayaran.index')
                ->with('success', 'Biodata tersimpan. Silakan lanjutkan pembayaran untuk jalur ' . $jalur->nama . '.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal proses pendaftaran awal: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())
                ->withInput();
        }
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
    // 3. PEMBAYARAN MANUAL (STEP 2 & 3)
    //    Hanya diakses jalur berbayar (is_free_registration = false)
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
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'bukti_pembayaran.required' => 'File bukti pembayaran wajib diisi.',
            'bukti_pembayaran.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        $pendaftar = DataPendaftar::find(session('pendaftar_id'));

        if ($request->filled('metode_pembayaran')) {
            $pendaftar->metode_pembayaran = $request->metode_pembayaran;
        }

        if ($request->hasFile('bukti_pembayaran')) {
            $file     = $request->file('bukti_pembayaran');
            $filename = $pendaftar->id . '_' . time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/bukti_bayar'), $filename);
            $pendaftar->bukti_pembayaran  = $filename;
            $pendaftar->status_pembayaran = 'Menunggu Validasi';
        }

        $pendaftar->save();
        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Harap tunggu proses validasi oleh Admin.');
    }

    // ==========================================================
    // 4. KONFIRMASI
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
    // 5. VALIDASI AKHIR & HASIL
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

    public function cetakLoA()
    {
        $pendaftar = DataPendaftar::find(session('pendaftar_id'));

        if (!$pendaftar || strpos($pendaftar->status_kelulusan, 'Lulus') === false || $pendaftar->status_kelulusan == 'Tidak Lulus') {
            return redirect()->route('dashboard.user')->with('error', 'Surat Keterangan Lulus belum tersedia.');
        }

        return view('user.cetak-loa', compact('pendaftar'));
    }
}