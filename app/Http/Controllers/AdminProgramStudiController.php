<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

class AdminProgramStudiController extends Controller
{
    /**
     * Menampilkan daftar program studi
     */
    public function index()
    {
        // Ambil semua data prodi dari database
        $data = Prodi::all();
        
        // Kirim data ke view resources/views/admin/prodi.blade.php
        return view('admin.prodi', compact('data'));
    }

    /**
     * Menyimpan prodi baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'jenjang'    => 'required',
            'akreditasi' => 'required',
            'kuota'      => 'required|integer',
            'biaya'      => 'required|numeric',
        ]);

        Prodi::create($validated);

        return redirect()->back()->with('success', 'Program Studi berhasil ditambahkan!');
    }

    /**
     * Mengupdate data prodi yang sudah ada
     */
    public function update(Request $request, $id)
    {
        $prodi = Prodi::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'jenjang'    => 'required',
            'akreditasi' => 'required',
            'kuota'      => 'required|integer',
            'biaya'      => 'required|numeric',
        ]);

        // Update data
        $prodi->update($validated);

        return redirect()->back()->with('success', 'Program Studi berhasil diperbarui!');
    }

    /**
     * Menghapus data prodi
     */
    public function destroy($id)
    {
        $prodi = Prodi::findOrFail($id);
        $prodi->delete();

        return redirect()->back()->with('success', 'Program Studi berhasil dihapus!');
    }
}