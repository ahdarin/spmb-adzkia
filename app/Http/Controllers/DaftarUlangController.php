<?php

namespace App\Http\Controllers;

use App\Models\DataPendaftar;
use App\Models\BiayaDaftarUlang;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DaftarUlangController extends Controller
{
    /**
     * Middleware guard dipakai di setiap method:
     * Fitur Daftar Ulang hanya bisa diakses jika status_kelulusan == 'Lulus'.
     */
    private function guardKelulusan(DataPendaftar $pendaftar)
    {
        $statusLulus = ['lulus pilihan 1', 'lulus pilihan 2'];

        if (!in_array(strtolower($pendaftar->status_kelulusan ?? ''), $statusLulus)) {
            return redirect()->route('dashboard.user')
                ->with('error', 'Fitur Daftar Ulang hanya bisa diakses oleh pendaftar yang telah dinyatakan Lulus.');
        }
        return null;
    }

    // ==========================================================
    // STEP 1: FORM DATA ORTU (Ayah / Ibu / Wali)
    // ==========================================================
    public function dataOrtuIndex()
    {
        $pendaftar = DataPendaftar::find(session('pendaftar_id'));
        if (!$pendaftar) {
            return redirect('/login')->with('error', 'Sesi Anda telah habis, silakan login kembali.');
        }

        if ($guard = $this->guardKelulusan($pendaftar)) {
            return $guard;
        }

        $dataOrtu = is_array($pendaftar->data_ortu)
            ? $pendaftar->data_ortu
            : (json_decode($pendaftar->data_ortu ?? '{}', true) ?? []);

        return view('user.daftar-ulang.data-ortu', compact('pendaftar', 'dataOrtu'));
    }

    public function simpanDataOrtu(Request $request)
    {
        $pendaftar = DataPendaftar::find(session('pendaftar_id'));
        if (!$pendaftar) {
            return redirect('/login')->with('error', 'Sesi Anda telah habis.');
        }

        if ($guard = $this->guardKelulusan($pendaftar)) {
            return $guard;
        }

    // Daftar opsi HARUS sama persis dengan yang ada di data-ortu.blade.php
    $opsiPekerjaan = [
        'Tidak Bekerja', 'PNS/PPPK', 'TNI/POLRI', 'Pegawai Swasta',
        'Wiraswasta', 'Petani/Pekebun', 'Nelayan', 'Buruh',
        'Guru/Dosen', 'Tenaga Kesehatan', 'Pensiunan',
        'Sudah Meninggal', 'Lainnya',
    ];

    $opsiPenghasilan = [
        'Tidak Berpenghasilan',
        'Kurang dari Rp500.000',
        'Rp500.000 – Rp999.999',
        'Rp1.000.000 – Rp1.999.999',
        'Rp2.000.000 – Rp4.999.999',
        'Rp5.000.000 – Rp9.999.999',
        'Rp10.000.000 – Rp14.999.999',
        'Rp15.000.000 – Rp19.999.999',
        'Rp20.000.000 atau lebih',
    ];

    $request->validate([
        // Ayah
        'ayah_nama'               => 'required|string|max:255',
        'ayah_pekerjaan'          => ['required', Rule::in($opsiPekerjaan)],
        'ayah_no_hp'              => 'required|string|max:20',
        'ayah_penghasilan'        => ['required', Rule::in($opsiPenghasilan)],
        'ayah_pendidikan_terakhir'=> 'required|string|max:100',
        'ayah_alamat'             => 'required|string',

        // Ibu
        'ibu_nama'                => 'required|string|max:255',
        'ibu_pekerjaan'           => ['required', Rule::in($opsiPekerjaan)],
        'ibu_no_hp'               => 'required|string|max:20',
        'ibu_penghasilan'         => ['required', Rule::in($opsiPenghasilan)],
        'ibu_pendidikan_terakhir' => 'required|string|max:100',
        'ibu_alamat'              => 'required|string',

        // Wali (opsional)
        'wali_nama'               => 'nullable|string|max:255',
        'wali_pekerjaan'          => ['nullable', Rule::in($opsiPekerjaan)],
        'wali_no_hp'              => 'nullable|string|max:20',
        'wali_penghasilan'        => ['nullable', Rule::in($opsiPenghasilan)],
        'wali_pendidikan_terakhir'=> 'nullable|string|max:100',
        'wali_alamat'             => 'nullable|string',
        'wali_hubungan'           => 'required_with:wali_nama|nullable|string|max:100',
    ], [
        'wali_hubungan.required_with' => 'Hubungan wali dengan siswa wajib diisi jika data wali diisi.',
        'ayah_pekerjaan.in'           => 'Pekerjaan ayah tidak valid, silakan pilih dari daftar.',
        'ibu_pekerjaan.in'            => 'Pekerjaan ibu tidak valid, silakan pilih dari daftar.',
        'ayah_penghasilan.in'         => 'Rentang penghasilan ayah tidak valid, silakan pilih dari daftar.',
        'ibu_penghasilan.in'          => 'Rentang penghasilan ibu tidak valid, silakan pilih dari daftar.',
    ]);

        $dataOrtu = [
            'ayah' => [
                'nama'                => $request->ayah_nama,
                'pekerjaan'           => $request->ayah_pekerjaan,
                'no_hp'               => $request->ayah_no_hp,
                'penghasilan'         => $request->ayah_penghasilan,
                'pendidikan_terakhir' => $request->ayah_pendidikan_terakhir,
                'alamat'              => $request->ayah_alamat,
            ],
            'ibu' => [
                'nama'                => $request->ibu_nama,
                'pekerjaan'           => $request->ibu_pekerjaan,
                'no_hp'               => $request->ibu_no_hp,
                'penghasilan'         => $request->ibu_penghasilan,
                'pendidikan_terakhir' => $request->ibu_pendidikan_terakhir,
                'alamat'              => $request->ibu_alamat,
            ],
            'wali' => $request->filled('wali_nama') ? [
                'nama'                => $request->wali_nama,
                'pekerjaan'           => $request->wali_pekerjaan,
                'no_hp'               => $request->wali_no_hp,
                'penghasilan'         => $request->wali_penghasilan,
                'pendidikan_terakhir' => $request->wali_pendidikan_terakhir,
                'alamat'              => $request->wali_alamat,
                'hubungan_wali'       => $request->wali_hubungan,
            ] : null,
        ];

        $pendaftar->update([
            'data_ortu' => json_encode($dataOrtu, JSON_UNESCAPED_UNICODE),
        ]);

        ActivityLogger::catat(
            'isi_data_ortu',
            "{$pendaftar->nama_lengkap} mengisi data orang tua/wali untuk daftar ulang.",
            ['modul' => 'Daftar Ulang', 'subjek' => $pendaftar]
        );

        return redirect()->route('daftar-ulang.pembayaran')
            ->with('success', 'Data orang tua/wali berhasil disimpan. Silakan lanjutkan ke pembayaran daftar ulang.');
    }

    // ==========================================================
    // STEP 2: PEMBAYARAN DAFTAR ULANG (manual, upload bukti)
    // ==========================================================
    public function pembayaranIndex()
    {
        $pendaftar = DataPendaftar::find(session('pendaftar_id'));
        if (!$pendaftar) {
            return redirect('/login')->with('error', 'Sesi Anda telah habis.');
        }

        if ($guard = $this->guardKelulusan($pendaftar)) {
            return $guard;
        }

        // Data ortu harus sudah diisi sebelum bisa bayar
        if (empty($pendaftar->data_ortu)) {
            return redirect()->route('daftar-ulang.data-ortu')
                ->with('error', 'Silakan lengkapi data orang tua/wali terlebih dahulu.');
        }

        // Cari biaya berdasarkan kombinasi prodi + jalur + gelombang + tahun
        // Prodi diambil dari pilihan_jurusan_1 (nama), cocokkan ke tabel prodis
        $prodi = \App\Models\Prodi::where('nama', $pendaftar->pilihan_jurusan_1)->first();

        // Gelombang aktif tahun ini
        $gelombang = \App\Models\Gelombang::where('tahun', date('Y'))
                        ->where('is_active', true)->first()
                  ?? \App\Models\Gelombang::where('tahun', date('Y'))->first();

        $biaya = null;

        if ($prodi && $pendaftar->jalur_id && $gelombang) {
            $biaya = BiayaDaftarUlang::where('prodi_id',     $prodi->id)
                                     ->where('jalur_id',     $pendaftar->jalur_id)
                                     ->where('gelombang_id', $gelombang->id)
                                     ->where('tahun',        date('Y'))
                                     ->first();
        }

        // Fallback: cari hanya berdasarkan jalur_id jika kombinasi lengkap tidak ditemukan
        if (!$biaya && $pendaftar->jalur_id) {
            $biaya = BiayaDaftarUlang::where('jalur_id', $pendaftar->jalur_id)->first();
        }

        return view('user.daftar-ulang.pembayaran', compact('pendaftar', 'biaya'));
    }

    public function prosesUploadBukti(Request $request)
    {
        $pendaftar = DataPendaftar::find(session('pendaftar_id'));
        if (!$pendaftar) {
            return redirect('/login')->with('error', 'Sesi Anda telah habis.');
        }

        if ($guard = $this->guardKelulusan($pendaftar)) {
            return $guard;
        }

        $request->validate([
            'bukti_daftar_ulang'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'metode_daftar_ulang' => 'nullable|string|max:100',
        ], [
            'bukti_daftar_ulang.required' => 'File bukti pembayaran daftar ulang wajib diisi.',
            'bukti_daftar_ulang.mimes'    => 'Format file harus JPG, PNG, atau PDF.',
            'bukti_daftar_ulang.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        try {
            $file     = $request->file('bukti_daftar_ulang');
            $filename = 'daftarulang_' . $pendaftar->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Pastikan folder ada
            if (!file_exists(public_path('uploads/bukti_daftar_ulang'))) {
                mkdir(public_path('uploads/bukti_daftar_ulang'), 0755, true);
            }

            $file->move(public_path('uploads/bukti_daftar_ulang'), $filename);

            $updateData = [
                'bukti_daftar_ulang'  => $filename,
                'metode_daftar_ulang' => $request->metode_daftar_ulang,
                'status_daftar_ulang' => 'Menunggu Validasi',
            ];

            // Auto-set pas_foto dari berkas_dokumen jika belum ada
            // Cari key yang mengandung kata "foto" atau "pas foto"
            if (empty($pendaftar->pas_foto) && !empty($pendaftar->berkas_dokumen)) {
                $berkas = is_array($pendaftar->berkas_dokumen)
                    ? $pendaftar->berkas_dokumen
                    : (json_decode($pendaftar->berkas_dokumen, true) ?? []);

                foreach ($berkas as $namaDoc => $pathDoc) {
                    if (stripos($namaDoc, 'foto') !== false || stripos($namaDoc, 'pas') !== false) {
                        $updateData['pas_foto'] = $pathDoc;
                        break;
                    }
                }
            }

            $pendaftar->update($updateData);

            ActivityLogger::catat(
                'upload_bukti_daftar_ulang',
                "{$pendaftar->nama_lengkap} mengunggah bukti pembayaran daftar ulang.",
                ['modul' => 'Daftar Ulang', 'subjek' => $pendaftar]
            );

            return redirect()->route('dashboard.user')
                ->with('success', '✅ Bukti pembayaran daftar ulang berhasil diunggah! Harap tunggu proses validasi oleh Admin. Anda akan mendapatkan NIM setelah berkas diverifikasi.');

        } catch (\Exception $e) {
            Log::error('Gagal upload bukti daftar ulang: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengunggah bukti pembayaran.')
                ->withInput();
        }
    }
}