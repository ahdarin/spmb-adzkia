<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Gelombang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminSettingController extends Controller
{
    // ══════════════════════════════════════════════════════════════
    // INDEX
    // ══════════════════════════════════════════════════════════════

    public function index()
    {
        $setting    = Setting::firstOrCreate(['id' => 1]);
        $gelombangs = Gelombang::orderBy('tahun', 'desc')
                               ->orderBy('id', 'desc')
                               ->get();

        // Jumlah gelombang per tahun untuk auto-generate nama di blade
        // Format: { "2026": 2, "2025": 3 }
        $countPerTahun = Gelombang::selectRaw('tahun, COUNT(*) as total')
                                  ->groupBy('tahun')
                                  ->pluck('total', 'tahun');

        return view('admin.settings', compact('setting', 'gelombangs', 'countPerTahun'));
    }

    // ══════════════════════════════════════════════════════════════
    // UPDATE SETTING UMUM (tab: umum & media)
    // ══════════════════════════════════════════════════════════════

    public function update(Request $request)
    {
        $request->validate([
            'email'          => 'nullable|email|max:100',
            'telepon'        => 'nullable|string|max:20',
            'tahun_akademik' => 'nullable|string|max:20',
            'akreditasi'     => 'nullable|string|max:20',
            'alamat'         => 'nullable|string',
            'video_profil'   => 'nullable|string|max:50',
            'link_maps'      => 'nullable|string',
            'brosur'         => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $setting = Setting::findOrFail(1);

        $data = $request->only([
            'tahun_akademik', 'akreditasi', 'video_profil',
            'alamat', 'telepon', 'email', 'link_maps',
            'maintenance_mode', 'pendaftaran_aktif',
        ]);

        // Handle upload brosur
        if ($request->hasFile('brosur')) {
            if ($setting->brosur_path && File::exists(public_path('uploads/docs/' . $setting->brosur_path))) {
                File::delete(public_path('uploads/docs/' . $setting->brosur_path));
            }
            $file     = $request->file('brosur');
            $fileName = 'Brosur_SPMB_' . date('Y') . '_' . time() . '.pdf';
            $file->move(public_path('uploads/docs'), $fileName);
            $data['brosur_path'] = $fileName;
        }

        $setting->update($data);

        return back()->with([
            'success' => 'Pengaturan berhasil disimpan!',
            'tab'     => $request->input('active_tab', 'umum'),
        ]);
    }

    // ══════════════════════════════════════════════════════════════
    // CRUD GELOMBANG (tab: gelombang)
    // ══════════════════════════════════════════════════════════════

    public function storeGelombang(Request $request)
    {
        $request->validate([
            'nama_gelombang'  => 'required|string|max:100',
            'tahun'           => 'required|digits:4|integer|min:2020|max:2040',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'is_active'       => 'required|boolean',
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal berakhir harus sama dengan atau setelah tanggal mulai.',
        ]);

        if ($request->boolean('is_active')) {
            Gelombang::where('tahun', $request->tahun)->update(['is_active' => false]);
        }

        Gelombang::create([
            'nama_gelombang'      => $request->nama_gelombang,
            'tahun'               => $request->tahun,
            'tanggal_mulai'       => $request->tanggal_mulai,
            'tanggal_selesai'     => $request->tanggal_selesai,
            'jumlah_jalur_dibuka' => 1,
            'is_active'           => $request->boolean('is_active'),
        ]);

        return back()->with(['success' => 'Gelombang berhasil ditambahkan!', 'tab' => 'gelombang']);
    }

    public function updateGelombang(Request $request, $id)
    {
        $gelombang = Gelombang::findOrFail($id);

        $request->validate([
            'nama_gelombang'  => 'required|string|max:100',
            'tahun'           => 'required|digits:4|integer|min:2020|max:2040',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'is_active'       => 'required|boolean',
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal berakhir harus sama dengan atau setelah tanggal mulai.',
        ]);

        if ($request->boolean('is_active')) {
            Gelombang::where('tahun', $request->tahun)
                     ->where('id', '!=', $id)
                     ->update(['is_active' => false]);
        }

        $gelombang->update([
            'nama_gelombang'  => $request->nama_gelombang,
            'tahun'           => $request->tahun,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'is_active'       => $request->boolean('is_active'),
        ]);

        return back()->with(['success' => 'Gelombang berhasil diperbarui!', 'tab' => 'gelombang']);
    }

    public function destroyGelombang($id)
    {
        $gelombang = Gelombang::findOrFail($id);

        if ($gelombang->is_active) {
            return back()->with([
                'error' => 'Tidak bisa menghapus gelombang yang sedang aktif. Nonaktifkan terlebih dahulu.',
                'tab'   => 'gelombang',
            ]);
        }

        if ($gelombang->biayaDaftarUlang()->exists()) {
            return back()->with([
                'error' => 'Tidak bisa menghapus gelombang yang masih memiliki data biaya daftar ulang.',
                'tab'   => 'gelombang',
            ]);
        }

        $nama = $gelombang->nama_gelombang;
        $gelombang->delete();

        return back()->with(['success' => "Gelombang \"{$nama}\" berhasil dihapus!", 'tab' => 'gelombang']);
    }

    // ══════════════════════════════════════════════════════════════
    // GANTI PASSWORD (tab: keamanan)
    // ══════════════════════════════════════════════════════════════

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ], [
            'new_password.min'       => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password saat ini yang Anda masukkan salah.'])
                ->with(['tab' => 'keamanan']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with(['success' => 'Password berhasil diperbarui!', 'tab' => 'keamanan']);
    }
}