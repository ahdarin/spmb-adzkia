<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File; // Wajib ditambahkan untuk menghapus file gambar lama

class AdminBeritaController extends Controller
{
    public function index() 
    {
        $data = Berita::latest()->get();
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
            'slug'            => Str::slug($request->judul), // <--- Tambahkan baris ini (Membuat URL otomatis dari judul)
            'ringkasan'       => $request->ringkasan, 
            'konten'          => $request->konten, // <--- Ubah 'isi' menjadi 'konten'
            'status'          => $request->status ?? 'Published',
            'thumbnail'       => $fileName,
            'tanggal_publish' => $request->tanggal_publish ?? now(),
        ]);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diterbitkan!');
    }

    // FUNGSI EDIT
    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita-edit', compact('berita'));
    }

    // FUNGSI UPDATE
    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required',
            'konten' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpg,png,jpeg|max:5120',
            
        ]);

        $data = [
            'judul'     => $request->judul,
            'kategori'  => $request->kategori,
            'slug'      => Str::slug($request->judul), // <--- Tambahkan baris ini
            'ringkasan' => $request->ringkasan,
            'konten'    => $request->konten, // <--- Ubah 'isi' menjadi 'konten'
        ];

        // Jika ada gambar baru yang diupload
        if ($request->hasFile('thumbnail')) {
            // Hapus gambar lama dari folder public/uploads/berita
            if ($berita->thumbnail && File::exists(public_path('uploads/berita/' . $berita->thumbnail))) {
                File::delete(public_path('uploads/berita/' . $berita->thumbnail));
            }

            // Simpan gambar baru
            $file = $request->file('thumbnail');
            $fileName = time() . '_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/berita'), $fileName);
            $data['thumbnail'] = $fileName;
        }

        $berita->update($data);
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui!');
    }

    // FUNGSI DELETE
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        // Hapus file fisik gambar
        if ($berita->thumbnail && File::exists(public_path('uploads/berita/' . $berita->thumbnail))) {
            File::delete(public_path('uploads/berita/' . $berita->thumbnail));
        }

        $berita->delete();
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus!');
    }
}