<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gelombang;
use App\Models\Prodi;
use App\Models\Jalur;
use App\Models\KomponenBiaya;
use App\Models\BiayaDaftarUlang;

class AdminMasterController extends Controller
{
    // ══════════════════════════════════════════════════════════════
    // MASTER GELOMBANG
    // ══════════════════════════════════════════════════════════════

    public function indexGelombang()
    {
        $gelombangs = Gelombang::orderBy('tahun', 'desc')
                               ->orderBy('id', 'desc')
                               ->get();

        return view('admin.master.gelombang', compact('gelombangs'));
    }

    public function storeGelombang(Request $request)
    {
        $request->validate([
            'nama_gelombang'      => 'required|string|max:100',
            'tahun'               => 'required|digits:4|integer',
            'tanggal_mulai'       => 'required|date',
            'tanggal_selesai'     => 'required|date|after_or_equal:tanggal_mulai',
            'jumlah_jalur_dibuka' => 'required|integer|min:1|max:20',
            'is_active'           => 'required|boolean',
        ]);

        // Jika is_active = true, non-aktifkan gelombang lain di tahun yang sama
        if ($request->boolean('is_active')) {
            Gelombang::where('tahun', $request->tahun)->update(['is_active' => false]);
        }

        Gelombang::create($request->only([
            'nama_gelombang', 'tahun', 'tanggal_mulai',
            'tanggal_selesai', 'jumlah_jalur_dibuka', 'is_active',
        ]));

        return back()->with('success', 'Gelombang berhasil ditambahkan!');
    }

    public function updateGelombang(Request $request, $id)
    {
        $gelombang = Gelombang::findOrFail($id);

        $request->validate([
            'nama_gelombang'      => 'required|string|max:100',
            'tahun'               => 'required|digits:4|integer',
            'tanggal_mulai'       => 'required|date',
            'tanggal_selesai'     => 'required|date|after_or_equal:tanggal_mulai',
            'jumlah_jalur_dibuka' => 'required|integer|min:1|max:20',
            'is_active'           => 'required|boolean',
        ]);

        if ($request->boolean('is_active')) {
            Gelombang::where('tahun', $request->tahun)
                     ->where('id', '!=', $id)
                     ->update(['is_active' => false]);
        }

        $gelombang->update($request->only([
            'nama_gelombang', 'tahun', 'tanggal_mulai',
            'tanggal_selesai', 'jumlah_jalur_dibuka', 'is_active',
        ]));

        return back()->with('success', 'Data gelombang berhasil diperbarui!');
    }

    public function destroyGelombang($id)
    {
        Gelombang::findOrFail($id)->delete();
        return back()->with('success', 'Gelombang berhasil dihapus!');
    }

    // ══════════════════════════════════════════════════════════════
    // MASTER BIAYA KULIAH PER PRODI (KomponenBiaya — tabel lama)
    // ══════════════════════════════════════════════════════════════

    public function indexBiaya()
    {
        $prodis = Prodi::with('komponenBiaya')->orderBy('nama')->get();
        return view('admin.master.biaya', compact('prodis'));
    }

    public function updateBiaya(Request $request, $prodi_id)
    {
        $request->validate([
            'spp'          => 'required|numeric|min:0',
            'uang_pangkal' => 'required|numeric|min:0',
        ]);

        KomponenBiaya::updateOrCreate(
            ['prodi_id' => $prodi_id],
            ['spp' => $request->spp, 'uang_pangkal' => $request->uang_pangkal]
        );

        return back()->with('success', 'Komponen biaya program studi berhasil diperbarui!');
    }

    // ══════════════════════════════════════════════════════════════
    // MASTER BIAYA DAFTAR ULANG  ← BARU
    // (prodi × jalur × gelombang × tahun)
    // ══════════════════════════════════════════════════════════════

    /**
     * Halaman utama master biaya daftar ulang.
     * Filter opsional: tahun dan gelombang_id.
     */
    public function indexBiayaDaftarUlang(Request $request)
    {
        $tahunFilter     = $request->input('tahun', date('Y'));
        $gelombangFilter = $request->input('gelombang_id');
        $prodiFilter     = $request->input('prodi_filter');
        $jalurFilter     = $request->input('jalur_filter');

        // Data untuk dropdown filter & form tambah
        $prodis     = Prodi::orderBy('nama')->get();
        $jalurs     = Jalur::where('is_active', true)->orderBy('nama_jalur')->get();
        $gelombangs = Gelombang::orderBy('tahun', 'desc')->orderBy('id', 'desc')->get();
        $tahunList  = Gelombang::selectRaw('DISTINCT tahun')->orderBy('tahun', 'desc')->pluck('tahun');

        // Query data biaya sesuai filter
        $query = BiayaDaftarUlang::with(['prodi', 'jalur', 'gelombang'])
                                 ->where('tahun', $tahunFilter);

        if ($gelombangFilter) {
            $query->where('gelombang_id', $gelombangFilter);
        }

        if ($prodiFilter) {
            $query->where('prodi_id', $prodiFilter);
        }

        if ($jalurFilter) {
            $query->where('jalur_id', $jalurFilter);
        }

        $biayaList = $query->orderBy('prodi_id')->orderBy('jalur_id')->get();

        // Kelompokkan per prodi untuk tampilan accordion
        $biayaPerProdi = $biayaList->groupBy('prodi_id')->map(function ($items) {
            return [
                'prodi'        => $items->first()->prodi,
                'items'        => $items,
                'total_terisi' => $items->where('total_biaya', '>', 0)->count(),
                'total_baris'  => $items->count(),
            ];
        });

        return view('admin.master.biaya-daftar-ulang', compact(
            'prodis', 'jalurs', 'gelombangs', 'tahunList',
            'tahunFilter', 'gelombangFilter', 'prodiFilter', 'jalurFilter',
            'biayaList', 'biayaPerProdi'
        ));
    }

    /**
     * Simpan / perbarui satu baris biaya daftar ulang.
     * Menggunakan updateOrCreate agar bisa dipakai untuk tambah & edit.
     */
    public function storeBiayaDaftarUlang(Request $request)
    {
        $request->validate([
            'prodi_id'                => 'required|exists:prodis,id',
            'jalur_id'                => 'required|exists:jalurs,id',
            'gelombang_id'            => 'required|exists:gelombangs,id',
            'tahun'                   => 'required|digits:4|integer',
            'spp_semester'            => 'required|numeric|min:0',
            'biaya_sarpras'           => 'required|numeric|min:0',
            'biaya_seragam_orientasi' => 'required|numeric|min:0',
        ], [
            'prodi_id.required'    => 'Program studi wajib dipilih.',
            'jalur_id.required'    => 'Jalur pendaftaran wajib dipilih.',
            'gelombang_id.required'=> 'Gelombang wajib dipilih.',
        ]);

        BiayaDaftarUlang::updateOrCreate(
            [
                'prodi_id'     => $request->prodi_id,
                'jalur_id'     => $request->jalur_id,
                'gelombang_id' => $request->gelombang_id,
                'tahun'        => $request->tahun,
            ],
            [
                'spp_semester'            => $request->spp_semester,
                'biaya_sarpras'           => $request->biaya_sarpras,
                'biaya_seragam_orientasi' => $request->biaya_seragam_orientasi,
                // total_biaya dihitung otomatis oleh boot() di Model
            ]
        );

        return back()->with('success', 'Data biaya daftar ulang berhasil disimpan!');
    }

    /**
     * Update inline satu record biaya (dipakai oleh modal edit).
     */
    public function updateBiayaDaftarUlang(Request $request, $id)
    {
        $biaya = BiayaDaftarUlang::findOrFail($id);

        $request->validate([
            'spp_semester'            => 'required|numeric|min:0',
            'biaya_sarpras'           => 'required|numeric|min:0',
            'biaya_seragam_orientasi' => 'required|numeric|min:0',
        ]);

        $biaya->update([
            'spp_semester'            => $request->spp_semester,
            'biaya_sarpras'           => $request->biaya_sarpras,
            'biaya_seragam_orientasi' => $request->biaya_seragam_orientasi,
        ]);

        return back()->with('success', 'Biaya daftar ulang berhasil diperbarui!');
    }

    /**
     * Hapus satu record biaya daftar ulang.
     */
    public function destroyBiayaDaftarUlang($id)
    {
        BiayaDaftarUlang::findOrFail($id)->delete();
        return back()->with('success', 'Data biaya daftar ulang berhasil dihapus!');
    }

    /**
     * Salin semua biaya dari satu gelombang ke gelombang lain.
     * Berguna saat awal tahun akademik baru.
     */
    public function salinBiaya(Request $request)
    {
        $request->validate([
            'sumber_gelombang_id' => 'required|exists:gelombangs,id',
            'tujuan_gelombang_id' => 'required|exists:gelombangs,id|different:sumber_gelombang_id',
            'tahun_tujuan'        => 'required|digits:4|integer',
        ]);

        $sumber = BiayaDaftarUlang::where('gelombang_id', $request->sumber_gelombang_id)->get();

        if ($sumber->isEmpty()) {
            return back()->with('error', 'Tidak ada data biaya di gelombang sumber untuk disalin.');
        }

        $disalin = 0;
        foreach ($sumber as $b) {
            BiayaDaftarUlang::updateOrCreate(
                [
                    'prodi_id'     => $b->prodi_id,
                    'jalur_id'     => $b->jalur_id,
                    'gelombang_id' => $request->tujuan_gelombang_id,
                    'tahun'        => $request->tahun_tujuan,
                ],
                [
                    'spp_semester'            => $b->spp_semester,
                    'biaya_sarpras'           => $b->biaya_sarpras,
                    'biaya_seragam_orientasi' => $b->biaya_seragam_orientasi,
                ]
            );
            $disalin++;
        }

        return back()->with('success', "{$disalin} data biaya berhasil disalin ke gelombang tujuan!");
    }
}