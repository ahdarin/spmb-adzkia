<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jalur extends Model
{
    use HasFactory;

    protected $table = 'jalurs';

    protected $fillable = [
        'nama_jalur',
        'kode_nim',
        'is_free_registration',
        'has_exam',
        'dokumen_syarat',
        'is_active',
    ];

    protected $casts = [
        'is_free_registration' => 'boolean',
        'has_exam'             => 'boolean',
        'is_active'            => 'boolean',
        'dokumen_syarat'       => 'array',  // Otomatis encode/decode JSON
    ];

    // ─── Relasi ───────────────────────────────────────────────────────────────

    public function biayaDaftarUlangs()
    {
        return $this->hasMany(BiayaDaftarUlang::class);
    }
}
