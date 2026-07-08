<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiayaDaftarUlang extends Model
{
    use HasFactory;

    protected $table = 'biaya_daftar_ulangs';

    protected $fillable = [
        'prodi_id',
        'jalur_id',
        'gelombang_id',
        'tahun',
        'spp_semester',
        'biaya_sarpras',
        'biaya_seragam_orientasi',
        // 'total_biaya' dikecualikan karena generated column (dihitung DB)
    ];

    protected $casts = [
        'spp_semester'            => 'integer',
        'biaya_sarpras'           => 'integer',
        'biaya_seragam_orientasi' => 'integer',
        'total_biaya'             => 'integer',
    ];

    // ─── Relasi ───────────────────────────────────────────────────────────────

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function jalur()
    {
        return $this->belongsTo(Jalur::class);
    }

    public function gelombang()
    {
        return $this->belongsTo(Gelombang::class);
    }

    // ─── Accessor ─────────────────────────────────────────────────────────────

    /** Format total_biaya ke Rupiah: "Rp 5.000.000" */
    public function getTotalBiayaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total_biaya, 0, ',', '.');
    }
}
