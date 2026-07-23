<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $guarded = [];

    /** Scope: filter berdasarkan jenis aktor (admin/pendaftar/system). */
    public function scopeAktor($query, ?string $type)
    {
        return $type ? $query->where('actor_type', $type) : $query;
    }

    /** Scope: pencarian bebas di nama aktor / deskripsi / modul. */
    public function scopeCari($query, ?string $keyword)
    {
        if (!$keyword) return $query;

        return $query->where(function ($q) use ($keyword) {
            $q->where('actor_nama', 'like', "%{$keyword}%")
              ->orWhere('deskripsi', 'like', "%{$keyword}%")
              ->orWhere('modul', 'like', "%{$keyword}%")
              ->orWhere('aktivitas', 'like', "%{$keyword}%");
        });
    }
}
