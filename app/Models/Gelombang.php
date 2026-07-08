<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gelombang extends Model
{
    use HasFactory;

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
        'tanggal_mulai'    => 'date',
        'tanggal_selesai'  => 'date',
        'is_active'        => 'boolean',
    ];

    // ─── Relasi ───────────────────────────────────────────────────────────────

    public function biayaDaftarUlangs()
    {
        return $this->hasMany(BiayaDaftarUlang::class);
    }

    // ─── Scope ────────────────────────────────────────────────────────────────

    /** Gelombang yang sedang aktif dan tanggalnya masih berjalan */
    public function scopeAktif($query)
    {
        return $query->where('is_active', true)
                     ->where('tanggal_mulai', '<=', now())
                     ->where('tanggal_selesai', '>=', now());
    }
}
