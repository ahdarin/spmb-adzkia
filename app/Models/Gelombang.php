<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gelombang extends Model
{
    protected $table = 'gelombangs';

    protected $fillable = [
        'nama_gelombang',
        'tahun',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_jalur_dibuka',
        'is_active',
    ];

    protected $casts = [
        'is_active'           => 'boolean',
        'tahun'               => 'integer',
        'jumlah_jalur_dibuka' => 'integer',
        'tanggal_mulai'       => 'date',
        'tanggal_selesai'     => 'date',
    ];

    // ── Relasi ─────────────────────────────────────────────────────

    public function biayaDaftarUlang(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BiayaDaftarUlang::class);
    }

    public function dataPendaftar(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DataPendaftar::class);
    }

    // ── Scope ──────────────────────────────────────────────────────

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    // ── Static Helper ──────────────────────────────────────────────

    public static function getAktif(): ?self
    {
        return static::where('is_active', true)
                     ->where('tahun', date('Y'))
                     ->first()
               ?? static::where('is_active', true)->latest()->first();
    }

    // ── Accessor ───────────────────────────────────────────────────

    public function getDurasiAttribute(): int
    {
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai);
    }

    public function getSisaHariAttribute(): int
    {
        return max(0, (int) now()->diffInDays($this->tanggal_selesai, false));
    }
}
