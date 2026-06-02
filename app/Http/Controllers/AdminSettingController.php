<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminSettingController extends Controller
{
    public function index()
    {
        $setting = Setting::firstOrCreate(['id' => 1]);
        return view('admin.settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::findOrFail(1);

        // Filter data yang hanya ada di database agar aman
        $data = $request->only([
            'tahun_akademik', 'akreditasi', 'video_profil', 
            'alamat', 'telepon', 'email', 'link_maps', 
            'maintenance_mode', 'pendaftaran_aktif', 
            'gelombang_1_buka', 'gelombang_1_tutup', 
            'gelombang_2_buka', 'gelombang_2_tutup', 
            'gelombang_3_buka', 'gelombang_3_tutup'
        ]);

        // Logika Upload Brosur
        if ($request->hasFile('brosur')) {
            // Hapus file lama jika ada
            if ($setting->brosur_path && File::exists(public_path('uploads/docs/' . $setting->brosur_path))) {
                File::delete(public_path('uploads/docs/' . $setting->brosur_path));
            }
            $file = $request->file('brosur');
            $fileName = 'Brosur_SPMB_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/docs'), $fileName);
            $data['brosur_path'] = $fileName;
        }

        $setting->update($data);

        // Redirect kembali ke tab terakhir yang dibuka admin
        return back()->with(['success' => 'Pengaturan sistem berhasil disimpan!', 'tab' => $request->active_tab ?? 'umum']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        // Cek apakah password lama sesuai
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini yang Anda masukkan salah.'])->with(['tab' => 'keamanan']);
        }

        // Simpan password baru
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with(['success' => 'Password admin berhasil diperbarui!', 'tab' => 'keamanan']);
    }
}