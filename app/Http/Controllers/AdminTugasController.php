<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminTugasController extends Controller
{
    public function index()
    {
        // Ambil semua user yang berstatus admin
        $admins = User::where('role', 'admin')->orWhere('role', 'superadmin')->get();
        return view('admin.tugas', compact('admins'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggung_jawab' => 'required'
        ]);

        $admin = User::findOrFail($id);
        $admin->update([
            'tanggung_jawab' => $request->tanggung_jawab
        ]);

        return redirect()->back()->with('success', 'Tanggung jawab admin berhasil diubah!');
    }
}