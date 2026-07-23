<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\Hash;

class AdminTugasController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->get();
        return view('admin.tugas', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'divisi'   => 'required|string',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
            'divisi'   => $request->divisi,
        ]);

        ActivityLogger::catat(
            'tambah_staf',
            "Akun staf admin \"{$user->name}\" (divisi: {$user->divisi}) berhasil ditambahkan.",
            ['modul' => 'Manajemen Staf', 'subjek' => $user]
        );

        return back()->with('success', 'Akun staf berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|string|email|max:255|unique:users,email,' . $id,
            'divisi' => 'required|string',
        ]);

        $admin->name   = $request->name;
        $admin->email  = $request->email;
        $admin->divisi = $request->divisi;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        ActivityLogger::catat(
            'edit_staf',
            "Data staf admin \"{$admin->name}\" (divisi: {$admin->divisi}) diperbarui.",
            ['modul' => 'Manajemen Staf', 'subjek' => $admin]
        );

        return back()->with('success', 'Data staf berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $admin = User::findOrFail($id);
        $nama  = $admin->name;

        ActivityLogger::catat(
            'hapus_staf',
            "Akun staf admin \"{$nama}\" (ID #{$admin->id}) dihapus.",
            ['modul' => 'Manajemen Staf', 'subjek_type' => \App\Models\User::class, 'subjek_id' => $admin->id]
        );

        $admin->delete();

        return back()->with('success', 'Akun staf berhasil dihapus!');
    }
}