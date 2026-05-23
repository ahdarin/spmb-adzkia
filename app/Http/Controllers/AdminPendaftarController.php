<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPendaftar;
use Carbon\Carbon;

class AdminPendaftarController extends Controller
{
    /**
     * Dashboard Utama Admin
     */
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
            'menungguValidasi' => (clone $query)->where('status_pembayaran', 'Validasi')->count(),
            'lulusSeleksi'     => (clone $query)->where('status_pembayaran', 'Terverifikasi')->count(),
            'pendapatan'       => (clone $query)->where('status_pembayaran', 'Terverifikasi')->sum('nominal_biaya') / 1000000, 
        ];

        return view('admin.dashboard', compact('stats', 'filter'));
    }

    public function index()
    {
        $users = DataPendaftar::latest()->get(); 
        $totalPendaftar   = DataPendaftar::count();
        $menungguValidasi = DataPendaftar::where('status_pembayaran', 'Validasi')->count();
        $lulusSeleksi     = DataPendaftar::where('status_pembayaran', 'Terverifikasi')->count();
        $pembayaranBelum  = DataPendaftar::where('status_pembayaran', 'Belum Bayar')->count();

        return view('admin.pendaftar', compact(
            'users', 'totalPendaftar', 'menungguValidasi', 'lulusSeleksi', 'pembayaranBelum'
        ));
    }
    

    /**
     * PERBAIKAN: Halaman Validasi Pembayaran
     */
    public function validasiPembayaranIndex(Request $request)
{
    $query = DataPendaftar::query();

    // 1. Filter Jalur
    if ($request->filled('jalur') && $request->jalur != 'Semua Jalur') {
        $query->where('jalur_pendaftaran', $request->jalur);
    }

    // 2. PERBAIKAN LOGIKA STATUS
    // Jika user memilih status tertentu, ikuti filter tersebut.
    // Jika tidak memilih (default), tampilkan yang PERLU TINDAKAN (Belum Bayar & Menunggu Validasi)
    if ($request->filled('status')) {
        $query->where('status_pembayaran', $request->status);
    } else {
        $query->whereIn('status_pembayaran', ['Belum Bayar', 'Menunggu Validasi']);
    }

    // 3. Pencarian
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('id', 'like', "%{$search}%");
        });
    }

    $pendaftarPending = $query->latest()->get();

    // 4. Proses data tambahan
    foreach ($pendaftarPending as $data) {
        $tanggalDaftar = $data->created_at ?? now();
        
        if ($tanggalDaftar->between('2026-01-01', '2026-03-31')) {
            $gelombang = 'Gelombang 1';
        } elseif ($tanggalDaftar->between('2026-04-01', '2026-06-30')) {
            $gelombang = 'Gelombang 2';
        } else {
            $gelombang = 'Gelombang 3';
        }

        $data->jalur_lengkap = $data->jalur_pendaftaran . ' ' . $gelombang;
        $data->bank_pilihan = $data->metode_pembayaran ?? 'MANDIRI'; 
        $data->nominal_biaya = $data->nominal_biaya ?? 250000;
    }

    return view('admin.validasi-pembayaran', [
    'pendaftarPending' => $pendaftarPending
]);
}

    public function setujuiPembayaran($id) {
    $pendaftar = \App\Models\DataPendaftar::findOrFail($id);
    $pendaftar->status_pembayaran = 'Terverifikasi';
    $pendaftar->save();

    return redirect()->back()->with('success', 'Data berhasil diverifikasi!');
}

    public function daftarUlangIndex()
{
    $pendaftarDaftarUlang = \App\Models\DataPendaftar::where('status_pembayaran', 'Terverifikasi')
                                ->latest()
                                ->get();


    return view('admin.validasi-daftar-ulang', compact('pendaftarDaftarUlang'));
}

    public function setujuiDaftarUlang($id)
{
    $pendaftar = \App\Models\DataPendaftar::findOrFail($id);
    
    // Pastikan nama kolom status sama dengan yang digunakan di halaman User
    $pendaftar->status_pendaftaran = 'Selesai';
    $pendaftar->save();

    return redirect()->back()->with('success', 'Data pendaftar telah diverifikasi.');
}



public function verifikasiBerkas(Request $request, $id)
{
    // 1. Cari data pendaftar
    $pendaftar = \App\Models\DataPendaftar::findOrFail($id);

    // 2. Update status menjadi Selesai
    $pendaftar->status_pendaftaran = 'Selesai';
    $pendaftar->save();

    // 3. Tambahkan redirect ke admin (tetap)
    return redirect()->route('admin.validasi-daftar-ulang')
                     ->with('success', 'Berkas pendaftar berhasil diverifikasi.');
}


public function tampilkanPengumuman()
{
    // Hanya menampilkan pendaftar yang statusnya sudah Selesai
    $dataSelesai = \App\Models\DataPendaftar::where('status_pendaftaran', 'Selesai')->get();

    return view('admin.pengumuman', compact('dataSelesai'));
}

    public function pengumumanIndex()
    {
        $pengumuman = DataPendaftar::all();
        return view('admin.pengumuman', compact('pengumuman'));
    }

    public function updateKelulusan(Request $request, $id)
    {
        $request->validate(['status_kelulusan' => 'required|in:Lulus,Tidak Lulus']);
        $pendaftar = DataPendaftar::findOrFail($id);
        $pendaftar->status_kelulusan = $request->status_kelulusan;
        $pendaftar->save();
        return redirect()->back()->with('success', 'Status berhasil diperbarui!');
    }
}