<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPendaftar;
use Carbon\Carbon;

class AdminPendaftarController extends Controller
{
    public function dashboard(Request $request)
    {
        $filter = $request->get('filter', 'Bulan Ini');
        $now = Carbon::now();
        $query = DataPendaftar::query();

        if ($filter == 'Hari Ini') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($filter == 'Minggu Ini') {
            $query->whereBetween('created_at', [$now->startOfWeek()->format('Y-m-d'), $now->endOfWeek()->format('Y-m-d')]);
        } else {
            $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
        }

        $stats = [
            'totalPendaftar'   => (clone $query)->count(),
            'menungguValidasi' => (clone $query)->where('status_pembayaran', 'Menunggu Validasi')->count(), // Diperbaiki stringnya
            'lulusSeleksi'     => (clone $query)->where('status_pembayaran', 'Terverifikasi')->count(),
            'pendapatan'       => (clone $query)->where('status_pembayaran', 'Terverifikasi')->sum('nominal_biaya') / 1000000, 
        ];

        return view('admin.dashboard', compact('stats', 'filter'));
    }

    public function index()
    {
        $users = DataPendaftar::latest()->get(); 
        $totalPendaftar   = DataPendaftar::count();
        $menungguValidasi = DataPendaftar::where('status_pembayaran', 'Menunggu Validasi')->count();
        $lulusSeleksi     = DataPendaftar::where('status_pembayaran', 'Terverifikasi')->count();
        $pembayaranBelum  = DataPendaftar::where('status_pembayaran', 'Belum Bayar')->count();

        return view('admin.pendaftar', compact('users', 'totalPendaftar', 'menungguValidasi', 'lulusSeleksi', 'pembayaranBelum'));
    }
    
    // ==========================================
    // VALIDASI PEMBAYARAN (KEUANGAN)
    // ==========================================
    public function validasiPembayaranIndex(Request $request)
    {
        $query = DataPendaftar::query();

        if ($request->filled('jalur') && $request->jalur != 'Semua Jalur') {
            $query->where('jalur_pendaftaran', $request->jalur);
        }

        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        } else {
            $query->whereIn('status_pembayaran', ['Belum Bayar', 'Menunggu Validasi']);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $pendaftarPending = $query->latest()->get();

        foreach ($pendaftarPending as $data) {
            $tanggalDaftar = $data->created_at ?? now();
            if ($tanggalDaftar->between('2026-01-01', '2026-03-31')) $gelombang = 'Gelombang 1';
            elseif ($tanggalDaftar->between('2026-04-01', '2026-06-30')) $gelombang = 'Gelombang 2';
            else $gelombang = 'Gelombang 3';

            $data->jalur_lengkap = $data->jalur_pendaftaran . ' ' . $gelombang;
            $data->bank_pilihan = $data->metode_pembayaran ?? 'Belum Dipilih'; 
            $data->nominal_biaya = $data->nominal_biaya ?? 250000;
        }

        return view('admin.validasi-pembayaran', compact('pendaftarPending'));
    }

    public function setujuiPembayaran($id) 
    {
        $pendaftar = DataPendaftar::findOrFail($id);
        $pendaftar->status_pembayaran = 'Terverifikasi';
        $pendaftar->save();

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi!');
    }

    // ==========================================
    // VALIDASI BIODATA (AKADEMIK / VERIFIKATOR)
    // ==========================================
    public function daftarUlangIndex()
    {
        $pendaftarDaftarUlang = DataPendaftar::where('status_pembayaran', 'Terverifikasi')
                                ->whereIn('status_pendaftaran', ['menunggu verifikasi', 'Selesai', 'Revisi'])  
                                ->latest()
                                ->get();

        return view('admin.validasi-daftar-ulang', compact('pendaftarDaftarUlang'));
    }

    public function setujuiDaftarUlang($id)
    {
        $pendaftar = DataPendaftar::findOrFail($id);
        $pendaftar->status_pendaftaran = 'Selesai';
        $pendaftar->save();

        return redirect()->back()->with('success', 'Berkas pendaftar telah diverifikasi.');
    }

    public function revisiDaftarUlang(Request $request, $id)
    {
        $request->validate(['pesan_revisi' => 'required|string']);
        $pendaftar = DataPendaftar::findOrFail($id);
        $pendaftar->update([
            'status_pendaftaran' => 'Revisi',
            'pesan_revisi'       => $request->pesan_revisi
        ]);
        return back()->with('success', 'Pesan revisi berhasil dikirim ke pendaftar.');
    }

    // ==========================================
    // PENGUMUMAN KELULUSAN
    // ==========================================
// ==========================================
    // PENGUMUMAN KELULUSAN
    // ==========================================
    public function pengumumanIndex()
    {
        // Ganti $dataSelesai kembali menjadi $pengumuman agar dikenali oleh Blade
        $pengumuman = DataPendaftar::where('status_pendaftaran', 'Selesai')->get();
        
        return view('admin.pengumuman', compact('pengumuman'));
    }

    public function updateKelulusan(Request $request, $id)
    {
        $request->validate(['status_kelulusan' => 'required|in:Lulus,Tidak Lulus']);
        $pendaftar = DataPendaftar::findOrFail($id);
        $pendaftar->status_kelulusan = $request->status_kelulusan;
        $pendaftar->save();
        return redirect()->back()->with('success', 'Status kelulusan berhasil diperbarui!');
    }
}