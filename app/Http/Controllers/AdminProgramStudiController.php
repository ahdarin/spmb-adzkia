<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class AdminProgramStudiController extends Controller
{
    public function index()
    {
        $data = Prodi::orderBy('nama')->get();
        return view('admin.prodi', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:255',
            'jenjang'    => 'required|string',
            'akreditasi' => 'required|string|max:100',
            'kuota'      => 'required|integer|min:0',
            'icon'       => 'nullable|string|max:50',
        ]);

        $prodi = Prodi::create([
            'nama'       => $request->nama,
            'jenjang'    => $request->jenjang,
            'akreditasi' => $request->akreditasi,
            'kuota'      => $request->kuota,
            'icon'       => $request->icon ?? 'book-open',
            'biaya'      => 0, // field masih ada di DB, isi 0 agar tidak error
        ]);

        ActivityLogger::catat(
            'tambah_prodi',
            "Program studi \"{$prodi->nama}\" ({$prodi->jenjang}) ditambahkan.",
            ['modul' => 'Master Prodi', 'subjek' => $prodi]
        );

        return back()->with('success', "Program studi \"{$prodi->nama}\" berhasil ditambahkan!");
    }

    public function update(Request $request, $id)
    {
        $prodi = Prodi::findOrFail($id);

        $request->validate([
            'nama'       => 'required|string|max:255',
            'jenjang'    => 'required|string',
            'akreditasi' => 'required|string|max:100',
            'kuota'      => 'required|integer|min:0',
            'icon'       => 'nullable|string|max:50',
        ]);

        $prodi->nama       = $request->nama;
        $prodi->jenjang    = $request->jenjang;
        $prodi->akreditasi = $request->akreditasi;
        $prodi->kuota      = $request->kuota;
        $prodi->icon       = $request->icon ?? $prodi->icon;
        $prodi->save();

        ActivityLogger::catat(
            'edit_prodi',
            "Program studi \"{$prodi->nama}\" ({$prodi->jenjang}) diperbarui.",
            ['modul' => 'Master Prodi', 'subjek' => $prodi]
        );

        return redirect('/admin/prodi')->with('success', "Program studi \"{$prodi->nama}\" berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $prodi = Prodi::findOrFail($id);
        $nama  = $prodi->nama;

        ActivityLogger::catat(
            'hapus_prodi',
            "Program studi \"{$nama}\" (ID #{$prodi->id}) dihapus.",
            ['modul' => 'Master Prodi']
        );

        $prodi->delete();

        return redirect('/admin/prodi')->with('success', "Program studi \"{$nama}\" berhasil dihapus!");
    }
}