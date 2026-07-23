<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class AdminBeritaController extends Controller
{
    public function index()
    {
        $data = Berita::latest()->get();
        return view('admin.berita', compact('data'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'judul'           => 'required|string|max:255',
                'kategori'        => 'required|in:Akademik,Beasiswa,Kegiatan,Informasi',
                'ringkasan'       => 'nullable|string|max:500',
                'konten'          => 'required|string',
                'status'          => 'required|in:Published,Draft',
                'tanggal_publish' => 'nullable|date',
                'thumbnail'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]);
        } catch (ValidationException $e) {
            // AJAX request → return JSON errors agar modal tetap terbuka
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }
            throw $e;
        }

        $fileName = $this->uploadThumbnail($request);

        $slug = $this->buatSlugUnik(Str::slug($request->judul));

        $berita = Berita::create([
            'judul'           => $request->judul,
            'kategori'        => $request->kategori,
            'slug'            => $slug,
            'ringkasan'       => $request->ringkasan,
            'konten'          => $request->konten,
            'status'          => $request->status,
            'thumbnail'       => $fileName,
            'tanggal_publish' => $request->tanggal_publish ?? now(),
        ]);

        ActivityLogger::catat(
            'tambah_berita',
            "Berita \"{$berita->judul}\" ditambahkan dengan status {$berita->status}.",
            ['modul' => 'Berita', 'subjek' => $berita]
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Berita \"{$berita->judul}\" berhasil disimpan!"]);
        }

        return redirect()->route('admin.berita.index')
            ->with('success', "Berita \"{$berita->judul}\" berhasil disimpan!");
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        try {
            $request->validate([
                'judul'           => 'required|string|max:255',
                'kategori'        => 'required|in:Akademik,Beasiswa,Kegiatan,Informasi',
                'ringkasan'       => 'nullable|string|max:500',
                'konten'          => 'required|string',
                'status'          => 'required|in:Published,Draft',
                'tanggal_publish' => 'nullable|date',
                'thumbnail'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]);
        } catch (ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }
            throw $e;
        }

        $slug = $this->buatSlugUnik(Str::slug($request->judul), $id);

        $berita->judul           = $request->judul;
        $berita->kategori        = $request->kategori;
        $berita->slug            = $slug;
        $berita->ringkasan       = $request->ringkasan;
        $berita->konten          = $request->konten;
        $berita->status          = $request->status;
        $berita->tanggal_publish = $request->tanggal_publish ?? $berita->tanggal_publish;

        if ($request->hasFile('thumbnail')) {
            // Hapus thumbnail lama
            if ($berita->thumbnail && File::exists(public_path('uploads/berita/' . $berita->thumbnail))) {
                File::delete(public_path('uploads/berita/' . $berita->thumbnail));
            }
            $berita->thumbnail = $this->uploadThumbnail($request);
        }

        $berita->save();

        ActivityLogger::catat(
            'edit_berita',
            "Berita \"{$berita->judul}\" diperbarui, status: {$berita->status}.",
            ['modul' => 'Berita', 'subjek' => $berita]
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Berita \"{$berita->judul}\" berhasil diperbarui!"]);
        }

        return redirect()->route('admin.berita.index')
            ->with('success', "Berita \"{$berita->judul}\" berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        if ($berita->thumbnail && File::exists(public_path('uploads/berita/' . $berita->thumbnail))) {
            File::delete(public_path('uploads/berita/' . $berita->thumbnail));
        }

        $judul = $berita->judul;
        ActivityLogger::catat('hapus_berita', "Berita \"{$judul}\" dihapus.", ['modul' => 'Berita']);
        $berita->delete();

        return redirect()->route('admin.berita.index')
            ->with('success', "Berita \"{$judul}\" berhasil dihapus!");
    }

    // ── Helpers ────────────────────────────────────────────────────

    private function uploadThumbnail(Request $request): ?string
    {
        if (!$request->hasFile('thumbnail')) return null;

        if (!File::exists(public_path('uploads/berita'))) {
            File::makeDirectory(public_path('uploads/berita'), 0755, true);
        }

        $file     = $request->file('thumbnail');
        $fileName = time() . '_' . Str::slug($request->judul ?? 'berita') . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/berita'), $fileName);

        return $fileName;
    }

    private function buatSlugUnik(string $slug, ?int $kecualiId = null): string
    {
        $base = $slug;
        $i    = 1;
        $query = Berita::where('slug', $slug);
        if ($kecualiId) $query->where('id', '!=', $kecualiId);

        while ($query->exists()) {
            $slug = $base . '-' . $i++;
            $query = Berita::where('slug', $slug);
            if ($kecualiId) $query->where('id', '!=', $kecualiId);
        }

        return $slug;
    }
}