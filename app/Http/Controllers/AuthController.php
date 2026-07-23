<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\DataPendaftar;
use App\Support\ActivityLogger;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        // Tangkap input (bisa dari login_admin 'email' atau login pendaftar 'login_input')
        $loginInput = $request->input('login_input') ?? $request->input('email');
        $password = $request->input('password');

        if (!$loginInput || !$password) {
            return back()->with('error', 'Kredensial tidak boleh kosong.');
        }

        // ========================================================
        // SKENARIO 1: CEK LOGIN SEBAGAI ADMIN (Tabel `users`)
        // ========================================================
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            if (Auth::attempt(['email' => $loginInput, 'password' => $password])) {
                $request->session()->regenerate();
                $user = Auth::user();

                ActivityLogger::catat('login', "{$user->name} login sebagai " . ($user->role ?? $user->divisi ?? 'admin'), [
                    'modul' => 'Autentikasi',
                ]);

                if ($user->role === 'super_admin') {
                    return redirect()->intended('/admin');
                } else {
                    if ($user->divisi === 'Keuangan') return redirect()->intended('/admin/validasi-pembayaran');
                    if ($user->divisi === 'Verifikator Berkas') return redirect()->intended('/admin/validasi-daftar-ulang');
                    if ($user->divisi === 'Humas & Informasi') return redirect()->intended('/admin/pengumuman');
                    return redirect()->intended('/admin');
                }
            }

            // Percobaan login admin gagal — tetap dicatat untuk keamanan,
            // TANPA password, hanya email yang dicoba.
            ActivityLogger::catat('login_gagal', "Percobaan login gagal untuk email {$loginInput}", [
                'modul'      => 'Autentikasi',
                'actor_type' => 'system',
            ]);
        }

        // ========================================================
        // SKENARIO 2: CEK LOGIN SEBAGAI PENDAFTAR (Tabel `data_pendaftars`)
        // ========================================================
        $pendaftar = DataPendaftar::where('no_pendaftaran', $loginInput)
                        ->orWhere('email', $loginInput)
                        ->first();

        if ($pendaftar && Hash::check($password, $pendaftar->password)) {
            
            // Hapus sesi admin jika sebelumnya ada yang login di browser yang sama
            if (Auth::check()) {
                Auth::logout();
            }

            // Buat Sesi Manual untuk Pendaftar
            $request->session()->regenerate();
            session([
                'is_pendaftar' => true,
                'pendaftar_id' => $pendaftar->id,
                'nama_pendaftar' => $pendaftar->nama_lengkap
            ]);

            ActivityLogger::catat('login', "{$pendaftar->nama_lengkap} ({$pendaftar->no_pendaftaran}) login", [
                'modul'  => 'Autentikasi',
                'subjek' => $pendaftar,
            ]);

            return redirect()->intended('/dashboard-user');
        }

        return back()->with('error', 'Login gagal. ID/Email atau password salah.');
    }

public function logout(Request $request)
    {
        // SKENARIO 1: Jika yang klik logout adalah ADMIN
        if (Auth::check()) {
            $user = Auth::user();
            ActivityLogger::catat('logout', "{$user->name} logout", ['modul' => 'Autentikasi']);

            Auth::logout(); // Matikan akses admin
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Langsung paksa arahkan ke login admin
            return redirect('/login-admin'); 
        }

        // SKENARIO 2: Jika yang klik logout adalah PENDAFTAR
        if (session('pendaftar_id')) {
            ActivityLogger::catat('logout', session('nama_pendaftar') . ' logout', ['modul' => 'Autentikasi']);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}