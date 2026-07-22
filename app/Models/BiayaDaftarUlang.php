<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiayaDaftarUlang extends Model
{
    protected $table = 'biaya_daftar_ulangs';

    protected $fillable = [
        'prodi_id',
        'jalur_id',
        'gelombang_id',
        'tahun',
        'spp_semester',
        'biaya_sarpras',
        'biaya_seragam_orientasi',
        'total_biaya',          // diisi otomatis oleh boot()
    ];

    protected $casts = [
        'spp_semester'            => 'integer',
        'biaya_sarpras'           => 'integer',
        'biaya_seragam_orientasi' => 'integer',
        'total_biaya'             => 'integer',
        'tahun'                   => 'integer',
    ];

    // ─── Auto-hitung total_biaya sebelum simpan ────────────────────
    protected static function boot(): void
    {
        parent::boot();

        $hitung = function (self $model): void {
            $model->total_biaya = $model->spp_semester
                                + $model->biaya_sarpras
                                + $model->biaya_seragam_orientasi;
        };

        static::creating($hitung);
        static::updating($hitung);
    }

    // ─── Relasi ────────────────────────────────────────────────────
    public function prodi(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function jalur(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Jalur::class);
    }

    public function gelombang(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Gelombang::class);
    }

    // ─── Helper: format rupiah ─────────────────────────────────────
    public function getSppFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->spp_semester, 0, ',', '.');
    }

    public function getSarprasFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->biaya_sarpras, 0, ',', '.');
    }

    public function getSeragamFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->biaya_seragam_orientasi, 0, ',', '.');
    }

    public function getTotalFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->total_biaya, 0, ',', '.');
    }
}
