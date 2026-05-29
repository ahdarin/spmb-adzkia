<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Fungsi untuk memproses login
    public function authenticate(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // 3. Cek Role dan Divisi untuk menentukan halaman tujuan (Redirect)
            
            if ($user->role === 'super_admin') {
                return redirect()->intended('/admin');
            } 
            elseif ($user->role === 'admin') {
                // Lempar ke halaman sesuai divisi tugasnya
                if ($user->divisi === 'Keuangan') {
                    return redirect()->intended('/admin/validasi-pembayaran');
                } elseif ($user->divisi === 'Verifikator Berkas') {
                    return redirect()->intended('/admin/validasi-daftar-ulang');
                } elseif ($user->divisi === 'Humas & Informasi') {
                    return redirect()->intended('/admin/pengumuman');
                } else {
                    return redirect()->intended('/admin'); // Default staf
                }
            } 
            else {
                // Jika user biasa (pendaftar mahasiswa)
                return redirect()->intended('/dashboard-user'); 
            }
        }

        // Jika email/password salah, kembalikan dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Fungsi untuk memproses logout
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login'); // Arahkan kembali ke halaman login setelah keluar
    }
}