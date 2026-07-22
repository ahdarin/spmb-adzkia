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

        // 1. Cari di DB lokal dulu (cepat)
        $lokal = Sekolah::where('nama_sekolah', 'like', "%{$q}%")
            ->orWhere('npsn', 'like', "%{$q}%")
            ->orWhere('kota', 'like', "%{$q}%")
            ->orderByRaw("CASE WHEN nama_sekolah LIKE ? THEN 0 ELSE 1 END", ["{$q}%"])
            ->limit(8)
            ->get(['id', 'npsn', 'nama_sekolah', 'kota', 'provinsi', 'bentuk', 'status'])
            ->map(fn($s) => [...$s->toArray(), 'source' => 'local']);

        // Kalau sudah cukup hasil dari lokal, kembalikan langsung
        if ($lokal->count() >= 5) {
            return response()->json($lokal->values());
        }

        // 2. Fallback ke API PDDikti untuk tambahan hasil
        $npsn_lokal = $lokal->pluck('npsn')->toArray();
        $apiResults = $this->searchFromApi($q, $npsn_lokal);

        return response()->json($lokal->concat($apiResults)->values());
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

    /**
     * Cari dari API PDDikti berdasarkan nama sekolah
     */
    private function searchFromApi(string $keyword, array $skipNpsn = []): array
    {
        try {
            $encoded  = urlencode($keyword);
            $url      = "https://api-sekolah-indonesia.vercel.app/sekolah?nama={$encoded}&limit=10";
            $ctx      = stream_context_create([
                'http' => ['timeout' => 5, 'ignore_errors' => true],
                'ssl'  => ['verify_peer' => false],
            ]);
            $response = @file_get_contents($url, false, $ctx);

            if (!$response) return [];

            $json = json_decode($response, true);
            if (empty($json['dataSekolah'])) return [];

            $results = [];
            foreach ($json['dataSekolah'] as $s) {
                $npsn = $s['npsn'] ?? null;
                if (!$npsn || in_array($npsn, $skipNpsn)) continue;

                $results[] = [
                    'id'           => null,
                    'npsn'         => $npsn,
                    'nama_sekolah' => ucwords(strtolower($s['nama'] ?? '')),
                    'kota'         => ucwords(strtolower($s['kota'] ?? $s['kabupaten_kota'] ?? '')),
                    'provinsi'     => ucwords(strtolower($s['propinsi'] ?? $s['provinsi'] ?? '')),
                    'bentuk'       => strtoupper($s['bentuk'] ?? ''),
                    'status'       => ucwords(strtolower($s['status'] ?? '')),
                    'alamat'       => $s['alamat_jalan'] ?? $s['alamat'] ?? null,
                    'source'       => 'api', // penanda dari API, belum ada di DB
                ];

                if (count($results) >= 5) break;
            }

            return $results;

        } catch (\Exception $e) {
            return [];
        }
    }
}