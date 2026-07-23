<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class AdminSekolahController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query  = Sekolah::query();

        if ($search) {
            $query->where('nama_sekolah', 'like', "%{$search}%")
                  ->orWhere('npsn', 'like', "%{$search}%");
        }

        $sekolahs = $query->orderBy('nama_sekolah')->paginate(20)->withQueryString();

        return view('admin.master.sekolah', compact('sekolahs', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'npsn'         => 'required|string|max:20|unique:sekolahs,npsn',
            'nama_sekolah' => 'required|string|max:255',
        ]);

        $sekolah = Sekolah::create($request->only(['npsn', 'nama_sekolah']));

        // ── Log Aktivitas ────────────────────────────────────────────
        ActivityLogger::catat(
            'tambah_sekolah',
            "Sekolah \"{$sekolah->nama_sekolah}\" (NPSN: {$sekolah->npsn}) ditambahkan ke master data.",
            ['modul' => 'Master Sekolah', 'subjek' => $sekolah]
        );

        return back()->with('success', 'Sekolah berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $sekolah = Sekolah::findOrFail($id);

        $request->validate([
            'npsn'         => 'required|string|max:20|unique:sekolahs,npsn,' . $id,
            'nama_sekolah' => 'required|string|max:255',
        ]);

        $sekolah->update($request->only(['npsn', 'nama_sekolah']));

        // ── Log Aktivitas ────────────────────────────────────────────
        ActivityLogger::catat(
            'edit_sekolah',
            "Data sekolah \"{$sekolah->nama_sekolah}\" (NPSN: {$sekolah->npsn}) diperbarui.",
            ['modul' => 'Master Sekolah', 'subjek' => $sekolah]
        );

        return back()->with('success', 'Data sekolah berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $sekolah = Sekolah::findOrFail($id);
        $nama    = $sekolah->nama_sekolah;

        // ── Log Aktivitas (sebelum hapus) ────────────────────────────
        ActivityLogger::catat(
            'hapus_sekolah',
            "Sekolah \"{$nama}\" (NPSN: {$sekolah->npsn}) dihapus dari master data.",
            ['modul' => 'Master Sekolah', 'subjek_type' => \App\Models\Sekolah::class, 'subjek_id' => $sekolah->id]
        );

        $sekolah->delete();

        return back()->with('success', "Sekolah \"{$nama}\" berhasil dihapus!");
    }
}