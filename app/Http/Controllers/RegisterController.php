<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DataPendaftar;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RegisterController extends Controller
{
public function showRegistrationForm()
    {
        // 1. Ambil data program studi
        $prodis = Prodi::all(); 

        // 2. Ambil atau siapkan data jalur khusus
        if (Schema::hasTable('jalurs')) {
            $jalurKhusus = DB::table('jalurs')->get()->groupBy('category');
        } else {
            $jalurKhusus = collect([
                'Program Beasiswa' => [
                    (object)['name' => 'Beasiswa Adzkia Unggul (BAU)'],
                    (object)['name' => 'Beasiswa PMDK'],
                    (object)['name' => 'Beasiswa Prestasi'],
                    (object)['name' => 'Beasiswa KIP-K'],
                ],
                'Rekognisi Pembelajaran Lampau (RPL)' => [
                    (object)['name' => 'RPL Afirmasi YASB'],
                    (object)['name' => 'RPL Afirmasi JSIT'],
                    (object)['name' => 'RPL Kelas Khusus'],
                ]
            ]);
        }
        
        // 3. Kirim data ke view
        return view('user.register', compact('prodis', 'jalurKhusus')); 
    }

    public function store(Request $request)
    {
        // 1. Validasi Inputan Mahasiswa
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'no_hp'    => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ], [
            'email.unique' => 'Email ini sudah terdaftar di sistem kami.',
            'password.min' => 'Password minimal harus berisikan 8 karakter.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                
                // Masukkan data dasar login ke tabel users
                $user = User::create([
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                // PERBAIKAN 1: Menggunakan DataPendaftar sesuai dengan import di atas
                DataPendaftar::create([
                    'user_id'       => $user->id, 
                    'no_hp'         => $request->no_hp,
                    'status'        => 'Belum Bayar', 
                    'program_studi' => '-', 
                    'jalur'         => '-',
                    'jalur_detail'  => '-',
                ]);

            auth()->login($user);
        });

        // Diarahkan langsung ke URL /pembayaran yang baru kita buat rutenya
        return redirect('/pembayaran')->with('success', 'Registrasi sukses! Silakan lakukan pembayaran.');

    } catch (\Exception $e) {
        return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
    }
}
}