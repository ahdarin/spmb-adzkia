<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;

class SekolahController extends Controller
{
    /**
     * Autocomplete search — dipanggil dari form pendaftar (AJAX)
     * Cari di DB lokal dulu, kalau kurang dari 5 hasil, fallback ke API PDDikti
     */
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $lokal = Sekolah::where('nama_sekolah', 'like', "%{$q}%")
            ->orWhere('npsn', 'like', "%{$q}%")
            ->orWhere('kota', 'like', "%{$q}%")
            ->orderByRaw("CASE WHEN nama_sekolah LIKE ? THEN 0 ELSE 1 END", ["{$q}%"])
            ->limit(8)
            ->get(['id', 'npsn', 'nama_sekolah', 'kota', 'provinsi', 'bentuk', 'status'])
            ->map(fn($s) => [...$s->toArray(), 'source' => 'local']);

        return response()->json($lokal->values());
    }

    /**
     * Simpan sekolah dari API ke DB (dipanggil saat user memilih hasil API)
     * Idempotent — kalau sudah ada, return yang existing
     */
    public function simpanDariApi(Request $request)
    {
        $request->validate([
            'npsn'         => 'required|string|max:20',
            'nama_sekolah' => 'required|string|max:255',
        ]);

        $sekolah = Sekolah::firstOrCreate(
            ['npsn' => $request->npsn],
            [
                'nama_sekolah' => $request->nama_sekolah,
                'alamat'       => $request->alamat,
                'kota'         => $request->kota,
                'provinsi'     => $request->provinsi,
                'bentuk'       => $request->bentuk,
                'status'       => $request->status,
            ]
        );

        return response()->json([
            'id'           => $sekolah->id,
            'npsn'         => $sekolah->npsn,
            'nama_sekolah' => $sekolah->nama_sekolah,
            'kota'         => $sekolah->kota,
            'provinsi'     => $sekolah->provinsi,
        ]);
    }
    
}