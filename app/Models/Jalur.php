<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jalur extends Model
{
    protected $table = 'jalurs';

    protected $fillable = [
        'nama_jalur',
        'kode_nim',
        'tipe_jalur',
        'is_free_registration',
        'has_exam',
        'dokumen_syarat',
        'is_active',
    ];

    protected $casts = [
        'is_free_registration' => 'boolean',
        'has_exam'             => 'boolean',
        'is_active'            => 'boolean',
        'dokumen_syarat'       => 'array',
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

    public function scopeByTipe($query, string $tipe)
    {
        return $query->where('tipe_jalur', $tipe);
    }

    // ── Helper ─────────────────────────────────────────────────────
    public function getTipeBadgeColorAttribute(): string
    {
        return match($this->tipe_jalur) {
            'RPL'          => 'bg-purple-100 text-purple-700',
            'Mitra Nagari' => 'bg-green-100 text-green-700',
            default        => 'bg-blue-100 text-blue-700',
        };
    }
}