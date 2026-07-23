<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;

class AdminSekolahController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $sekolahs = Sekolah::query()
            ->when($search, fn($q) => $q->where('nama_sekolah', 'like', "%$search%")
                                        ->orWhere('npsn', 'like', "%$search%")
                                        ->orWhere('kota', 'like', "%$search%"))
            ->orderBy('nama_sekolah')
            ->paginate(20)
            ->withQueryString();

        return view('admin.master.sekolah', compact('sekolahs', 'search'));
    }

    /**
     * Ambil data sekolah dari API publik "api-sekolah-indonesia" berdasarkan NPSN.
     * Endpoint: https://api-sekolah-indonesia.vercel.app/sekolah/{npsn}
     *
     * PENTING: response API ini TIDAK dibungkus key "dataSekolah", dan field
     * nama sekolah adalah "sekolah" (bukan "nama"/"nama_sekolah").
     * Contoh field asli: propinsi, kabupaten_kota, npsn, sekolah, bentuk,
     * status, alamat_jalan.
     *
     * API bisa mengembalikan:
     *  - object tunggal              -> { "npsn": "...", "sekolah": "...", ... }
     *  - array berisi satu object    -> [ { "npsn": "...", ... } ]
     *  - array kosong / null         -> tidak ditemukan
     */
    public function cariNpsn(Request $request)
    {
        $request->validate(['npsn' => 'required|digits_between:8,10']);
        $npsn = $request->npsn;

        // Cek apakah sudah ada di DB
        $existing = Sekolah::where('npsn', $npsn)->first();
        if ($existing) {
            return response()->json([
                'status'  => 'exists',
                'message' => 'Sekolah sudah ada di database.',
                'data'    => $existing,
            ]);
        }

        // Hit API publik
        try {
            $url      = "https://api-sekolah-indonesia.vercel.app/sekolah/{$npsn}";
            $response = @file_get_contents($url, false, stream_context_create([
                'http' => ['timeout' => 8, 'ignore_errors' => true],
                'ssl'  => ['verify_peer' => false],
            ]));

            if ($response === false) {
                throw new \Exception('Tidak dapat menghubungi API.');
            }

            $data = json_decode($response, true);

            // Normalisasi: API kadang mengembalikan array (list), ambil elemen pertama
            $s = null;
            if (is_array($data) && isset($data[0]) && is_array($data[0])) {
                $s = $data[0];
            } elseif (is_array($data) && isset($data['npsn'])) {
                $s = $data;
            } elseif (isset($data['dataSekolah'])) { // jaga-jaga jika versi API lama
                $s = $data['dataSekolah'];
            }

            if ($s) {
                return response()->json([
                    'status' => 'found',
                    'data'   => [
                        'npsn'         => $s['npsn']           ?? $npsn,
                        'nama_sekolah' => $s['sekolah']         ?? $s['nama'] ?? $s['nama_sekolah'] ?? '-',
                        'alamat'       => trim($s['alamat_jalan'] ?? $s['alamat'] ?? ''),
                        'kota'         => trim($s['kabupaten_kota'] ?? $s['kota'] ?? ''),
                        'provinsi'     => trim($s['propinsi'] ?? ''),
                        'bentuk'       => $s['bentuk'] ?? '',
                        'status'       => $s['status'] === 'N' ? 'Negeri' : ($s['status'] === 'S' ? 'Swasta' : ($s['status'] ?? '')),
                    ],
                ]);
            }

            return response()->json(['status' => 'not_found', 'message' => 'NPSN tidak ditemukan di database sekolah.'], 404);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'npsn'         => 'required|digits_between:8,10|unique:sekolahs,npsn',
            'nama_sekolah' => 'required|string|max:255',
            'alamat'       => 'nullable|string|max:500',
            'kota'         => 'nullable|string|max:100',
            'provinsi'     => 'nullable|string|max:100',
            'bentuk'       => 'nullable|string|max:50',
            'status'       => 'nullable|string|max:20',
        ], [
            'npsn.unique' => 'NPSN ini sudah terdaftar di database.',
        ]);

        Sekolah::create($request->only([
            'npsn','nama_sekolah','alamat','kota','provinsi','bentuk','status'
        ]));

        return back()->with('success', "Sekolah \"{$request->nama_sekolah}\" berhasil ditambahkan.");
    }

    public function update(Request $request, $id)
    {
        $sekolah = Sekolah::findOrFail($id);

        $request->validate([
            'npsn'         => "required|digits_between:8,10|unique:sekolahs,npsn,{$id}",
            'nama_sekolah' => 'required|string|max:255',
            'alamat'       => 'nullable|string|max:500',
            'kota'         => 'nullable|string|max:100',
            'provinsi'     => 'nullable|string|max:100',
        ]);

        $sekolah->update($request->only(['npsn','nama_sekolah','alamat','kota','provinsi','bentuk','status']));

        return back()->with('success', "Data sekolah berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $sekolah = Sekolah::findOrFail($id);
        $nama    = $sekolah->nama_sekolah;
        $sekolah->delete();

        return back()->with('success', "Sekolah \"{$nama}\" berhasil dihapus.");
    }
}