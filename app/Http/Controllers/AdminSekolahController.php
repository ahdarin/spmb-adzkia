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
     * Ambil data sekolah dari API PDDikti / Kemdikbud berdasarkan NPSN
     * Endpoint publik: https://api-sekolah-indonesia.vercel.app/sekolah/{npsn}
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

        // Hit API publik Kemdikbud
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

            // Format respons API sekolah-indonesia
            if (isset($data['dataSekolah'])) {
                $s = $data['dataSekolah'];
                return response()->json([
                    'status' => 'found',
                    'data'   => [
                        'npsn'         => $s['npsn']         ?? $npsn,
                        'nama_sekolah' => $s['nama']         ?? $s['nama_sekolah'] ?? '-',
                        'alamat'       => $s['alamat_jalan'] ?? $s['alamat'] ?? '',
                        'kota'         => $s['kota']         ?? $s['kabupaten_kota'] ?? '',
                        'provinsi'     => $s['propinsi']     ?? $s['provinsi'] ?? '',
                        'bentuk'       => $s['bentuk']       ?? '',
                        'status'       => $s['status']       ?? '',
                    ],
                ]);
            }

            return response()->json(['status' => 'not_found', 'message' => 'NPSN tidak ditemukan di database PDDikti.'], 404);

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