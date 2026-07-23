<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as RequestFacade;

/**
 * Helper pencatat aktivitas. Dipanggil manual di titik-titik penting
 * (login, logout, approve/reject, create/update/delete data penting) —
 * BUKAN lewat middleware global, supaya log tidak penuh noise dari setiap
 * page view biasa dan cuma berisi aksi yang benar-benar berarti buat admin.
 *
 * CONTOH PAKAI:
 *   ActivityLogger::catat('setujui_pembayaran', "Menyetujui pembayaran {$pendaftar->nama_lengkap}", [
 *       'modul'   => 'Pembayaran',
 *       'subjek'  => $pendaftar,
 *   ]);
 *
 * Otomatis mendeteksi siapa aktornya:
 *  - Kalau ada admin login (Auth::check())      -> actor_type = 'admin'
 *  - Kalau ada sesi pendaftar (session pendaftar_id) -> actor_type = 'pendaftar'
 *  - Kalau tidak keduanya (mis. dipanggil dari job/console) -> 'system'
 */
class ActivityLogger
{
    public static function catat(string $aktivitas, ?string $deskripsi = null, array $opsi = []): ActivityLog
    {
        [$actorType, $actorId, $actorNama, $actorRole] = self::deteksiAktor();

        $subjek = $opsi['subjek'] ?? null;

        return ActivityLog::create([
            'actor_type'   => $opsi['actor_type']  ?? $actorType,
            'actor_id'     => $opsi['actor_id']    ?? $actorId,
            'actor_nama'   => $opsi['actor_nama']  ?? $actorNama,
            'actor_role'   => $opsi['actor_role']  ?? $actorRole,
            'aktivitas'    => $aktivitas,
            'modul'        => $opsi['modul'] ?? null,
            'deskripsi'    => $deskripsi,
            'subjek_type'  => $subjek ? get_class($subjek) : ($opsi['subjek_type'] ?? null),
            'subjek_id'    => $subjek?->id ?? ($opsi['subjek_id'] ?? null),
            'ip_address'   => RequestFacade::ip(),
            'user_agent'   => substr((string) RequestFacade::header('User-Agent'), 0, 255),
        ]);
    }

    /** @return array{0: string, 1: int|null, 2: string|null, 3: string|null} */
    protected static function deteksiAktor(): array
    {
        if (Auth::check()) {
            $admin = Auth::user();
            return ['admin', $admin->id, $admin->name ?? $admin->email, $admin->role ?? $admin->divisi ?? null];
        }

        if (session('pendaftar_id')) {
            return [
                'pendaftar',
                session('pendaftar_id'),
                session('nama_pendaftar'),
                null,
            ];
        }

        return ['system', null, null, null];
    }
}
