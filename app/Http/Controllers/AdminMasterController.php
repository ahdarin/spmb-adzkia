<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gelombang;
use App\Models\Prodi;
use App\Models\KomponenBiaya;

class AdminMasterController extends Controller
{
    // ==========================================
    // BAGIAN MASTER GELOMBANG
    // ==========================================
    public function indexGelombang()
    {
        $gelombangs = Gelombang::orderBy('id', 'desc')->get();
        return view('admin.master.gelombang', compact('gelombangs'));
    }

    public function storeGelombang(Request $request)
    {
        $request->validate([
            'nama_gelombang' => 'required|string|max:255',
            'diskon_spp' => 'required|numeric',
            'diskon_uang_pangkal' => 'required|numeric',
            'is_active' => 'required|boolean',
        ]);

        Gelombang::create($request->all());
        return back()->with('success', 'Data Gelombang berhasil ditambahkan!');
    }

    public function updateGelombang(Request $request, $id)
    {
        $gelombang = Gelombang::findOrFail($id);
        $gelombang->update($request->all());
        return back()->with('success', 'Data Gelombang berhasil diupdate!');
    }

    public function destroyGelombang($id)
    {
        Gelombang::findOrFail($id)->delete();
        return back()->with('success', 'Data Gelombang berhasil dihapus!');
    }

    // ==========================================
    // BAGIAN MASTER BIAYA KULIAH (PER PRODI)
    // ==========================================
    public function indexBiaya()
    {
        // Ambil semua prodi beserta komponen biayanya
        $prodis = Prodi::with('komponenBiaya')->get();
        return view('admin.master.biaya', compact('prodis'));
    }

    public function updateBiaya(Request $request, $prodi_id)
    {
        $request->validate([
            'spp' => 'required|numeric',
            'uang_pangkal' => 'required|numeric',
        ]);

        // Update jika ada, atau Create jika belum ada
        KomponenBiaya::updateOrCreate(
            ['prodi_id' => $prodi_id],
            [
                'spp' => $request->spp,
                'uang_pangkal' => $request->uang_pangkal
            ]
        );

        return back()->with('success', 'Komponen Biaya Program Studi berhasil diupdate!');
    }
}