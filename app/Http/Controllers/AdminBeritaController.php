<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AdminBeritaController extends Controller
{
    public function index() 
    {
        $data = Berita::latest()->get(); // MENGAMBIL DATA ASLI DARI DATABASE
        return view('admin.berita', compact('data'));
    }

    public function create()
    {
        return view('admin.berita-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required',
            'konten' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpg,png,jpeg|max:5120',
        ]);

        $fileName = null;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $fileName = time() . '_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/berita'), $fileName);
        }

        Berita::create([
            'judul'           => $request->judul,
            'kategori'        => $request->kategori,
            'slug'            => Str::slug($request->judul), // PENTING UNTUK LINK BERITA
            'ringkasan'       => $request->ringkasan, 
            'konten'          => $request->konten, // DISESUAIKAN DENGAN NAMA DATABASE
            'status'          => $request->status ?? 'Published', // STATUS DRAFT/PUBLISH DARI TOMBOL
            'thumbnail'       => $fileName,
            'tanggal_publish' => $request->tanggal_publish ?? now(),
        ]);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diterbitkan!');
    }

    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita-edit', compact('berita'));
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        $data = [
            'judul'           => $request->judul,
            'kategori'        => $request->kategori,
            'slug'            => Str::slug($request->judul),
            'ringkasan'       => $request->ringkasan, 
            'konten'          => $request->konten,
            'status'          => $request->status ?? 'Published',
            'tanggal_publish' => $request->tanggal_publish ?? $berita->tanggal_publish,
        ];

        if ($request->hasFile('thumbnail')) {
            if ($berita->thumbnail && File::exists(public_path('uploads/berita/' . $berita->thumbnail))) {
                File::delete(public_path('uploads/berita/' . $berita->thumbnail));
            }
            $file = $request->file('thumbnail');
            $fileName = time() . '_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/berita'), $fileName);
            $data['thumbnail'] = $fileName;
        }

        $berita->update($data);
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        if ($berita->thumbnail && File::exists(public_path('uploads/berita/' . $berita->thumbnail))) {
            File::delete(public_path('uploads/berita/' . $berita->thumbnail));
        }
        $berita->delete();
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus!');
    }
}