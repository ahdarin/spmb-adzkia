<?php

namespace App\Http\Controllers;

use App\Models\Jalur;
use Illuminate\Http\Request;

class AdminJalurController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->input('search');
        $tipeFilter = $request->input('tipe');

        $query = Jalur::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_jalur', 'like', "%{$search}%")
                  ->orWhere('kode_nim',   'like', "%{$search}%");
            });
        }

        if ($tipeFilter) {
            $query->where('tipe_jalur', $tipeFilter);
        }

        $jalurs = $query->orderBy('tipe_jalur')->orderBy('id')->paginate(15)->withQueryString();
        $tipes  = Jalur::select('tipe_jalur')->distinct()->orderBy('tipe_jalur')->pluck('tipe_jalur');

        return view('admin.master.jalur', compact('jalurs', 'tipes', 'search', 'tipeFilter'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jalur'           => 'required|string|max:100',
            'kode_nim'             => 'required|string|max:10|unique:jalurs,kode_nim',
            'tipe_jalur'           => 'required|in:Umum,RPL,Mitra Nagari',
            'is_free_registration' => 'required|boolean',
            'has_exam'             => 'required|boolean',
            'is_active'            => 'required|boolean',
            'dokumen_syarat'       => 'nullable|array',
            'dokumen_syarat.*'     => 'string|max:100',
        ], [
            'kode_nim.unique' => 'Kode NIM sudah digunakan oleh jalur lain.',
        ]);

        $data = $request->except('dokumen_syarat');
        $data['dokumen_syarat'] = $request->input('dokumen_syarat', []);

        Jalur::create($data);

        return back()->with('success', 'Jalur "' . $request->nama_jalur . '" berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $jalur = Jalur::findOrFail($id);

        $request->validate([
            'nama_jalur'           => 'required|string|max:100',
            'kode_nim'             => 'required|string|max:10|unique:jalurs,kode_nim,' . $id,
            'tipe_jalur'           => 'required|in:Umum,RPL,Mitra Nagari',
            'is_free_registration' => 'required|boolean',
            'has_exam'             => 'required|boolean',
            'is_active'            => 'required|boolean',
            'dokumen_syarat'       => 'nullable|array',
            'dokumen_syarat.*'     => 'string|max:100',
        ], [
            'kode_nim.unique' => 'Kode NIM sudah digunakan oleh jalur lain.',
        ]);

        $data = $request->except('dokumen_syarat');
        $data['dokumen_syarat'] = $request->input('dokumen_syarat', []);

        $jalur->update($data);

        return back()->with('success', 'Jalur "' . $request->nama_jalur . '" berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jalur = Jalur::findOrFail($id);

        // Cek apakah jalur masih dipakai di data pendaftar atau biaya
        $terpakai = $jalur->dataPendaftar()->exists() || $jalur->biayaDaftarUlang()->exists();
        if ($terpakai) {
            return back()->with('error', 'Jalur "' . $jalur->nama_jalur . '" tidak bisa dihapus karena masih digunakan oleh data pendaftar atau biaya.');
        }

        $nama = $jalur->nama_jalur;
        $jalur->delete();

        return back()->with('success', 'Jalur "' . $nama . '" berhasil dihapus!');
    }
}