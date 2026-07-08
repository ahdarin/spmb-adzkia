<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomponenBiaya extends Model
{
    use HasFactory;

    protected $fillable = [
        'prodi_id', 
        'spp', 
        'uang_pangkal'
    ];

    // Relasi: Komponen Biaya ini milik satu Prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }
}