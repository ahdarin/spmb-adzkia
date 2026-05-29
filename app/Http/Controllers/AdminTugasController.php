<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminTugasController extends Controller
{
    // Menampilkan halaman manajemen divisi (Hanya mengambil yang rolenya 'admin')
    public function index()
    {
        // Asumsi Super Admin sedang login, kita tampilkan semua staf admin
        $admins = User::where('role', 'admin')->get();
        return view('admin.tugas', compact('admins'));
    }

    // Menyimpan akun staf baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'divisi' => 'required|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'divisi' => $request->divisi,
        ]);

        return back()->with('success', 'Akun staf berhasil ditambahkan!');
    }

    // Mengupdate data staf
    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'divisi' => 'required|string',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->divisi = $request->divisi;
        
        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return back()->with('success', 'Data staf berhasil diperbarui!');
    }

    // Menghapus akun staf
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Akun staf berhasil dihapus!');
    }
}