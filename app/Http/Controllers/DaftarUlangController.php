<?php

namespace App\Http\Controllers;

use App\Models\DataPendaftar;
use App\Models\BiayaDaftarUlang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DaftarUlangController extends Controller
{
    /**
     * Middleware guard dipakai di setiap method:
     * Fitur Daftar Ulang hanya bisa diakses jika status_kelulusan == 'Lulus'.
     */
    private function guardKelulusan(DataPendaftar $pendaftar)
    {
        if (strtolower($pendaftar->status_kelulusan) !== 'lulus') {
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

        // -----------------------------------------------------
        // VALIDASI
        // Ayah & Ibu wajib. Wali bersifat opsional (nullable),
        // tapi jika salah satu field wali diisi, nama_wali wajib.
        // -----------------------------------------------------
        $request->validate([
            // Ayah
            'ayah_nama'               => 'required|string|max:255',
            'ayah_pekerjaan'          => 'required|string|max:255',
            'ayah_no_hp'              => 'required|string|max:20',
            'ayah_penghasilan'        => 'required|numeric|min:0',
            'ayah_pendidikan_terakhir'=> 'required|string|max:100',
            'ayah_alamat'             => 'required|string',

            // Ibu
            'ibu_nama'                => 'required|string|max:255',
            'ibu_pekerjaan'           => 'required|string|max:255',
            'ibu_no_hp'               => 'required|string|max:20',
            'ibu_penghasilan'         => 'required|numeric|min:0',
            'ibu_pendidikan_terakhir' => 'required|string|max:100',
            'ibu_alamat'              => 'required|string',

            // Wali (opsional)
            'wali_nama'               => 'nullable|string|max:255',
            'wali_pekerjaan'          => 'nullable|string|max:255',
            'wali_no_hp'              => 'nullable|string|max:20',
            'wali_penghasilan'        => 'nullable|numeric|min:0',
            'wali_pendidikan_terakhir'=> 'nullable|string|max:100',
            'wali_alamat'             => 'nullable|string',
            'wali_hubungan'           => 'required_with:wali_nama|nullable|string|max:100',
        ], [
            'wali_hubungan.required_with' => 'Hubungan wali dengan siswa wajib diisi jika data wali diisi.',
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

        // Ambil nominal biaya daftar ulang sesuai jalur pendaftar
        $biaya = BiayaDaftarUlang::where('jalur_id', $pendaftar->jalur_id)->first();

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
            $file->move(public_path('uploads/bukti_daftar_ulang'), $filename);

            $pendaftar->update([
                'bukti_daftar_ulang'  => $filename,
                'metode_daftar_ulang' => $request->metode_daftar_ulang,
                'status_daftar_ulang' => 'Menunggu Validasi',
            ]);

            return redirect()->route('dashboard.user')
                ->with('success', 'Bukti pembayaran daftar ulang berhasil diunggah! Harap tunggu proses validasi oleh Admin.');

        } catch (\Exception $e) {
            Log::error('Gagal upload bukti daftar ulang: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengunggah bukti pembayaran.')
                ->withInput();
        }
    }
}
