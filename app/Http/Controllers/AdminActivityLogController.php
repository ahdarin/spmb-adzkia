<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AdminActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::query()
            ->aktor($request->get('actor_type'))
            ->cari($request->get('search'))
            ->latest('created_at');

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }
        if ($request->filled('aktivitas')) {
            $query->where('aktivitas', $request->aktivitas);
        }

        $logs = $query->paginate(30)->withQueryString();

        // Daftar jenis aktivitas unik untuk dropdown filter
        $daftarAktivitas = ActivityLog::select('aktivitas')
            ->distinct()
            ->orderBy('aktivitas')
            ->pluck('aktivitas');

        return view('admin.activity-log', compact('logs', 'daftarAktivitas'));
    }

    /** Hapus log yang lebih lama dari 90 hari (opsional, panggil manual/scheduler). */
    public function bersihkanLama()
    {
        $terhapus = ActivityLog::where('created_at', '<', now()->subDays(90))->delete();

        return back()->with('success', "{$terhapus} log lama (>90 hari) berhasil dibersihkan.");
    }
}
