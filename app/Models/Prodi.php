<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodis';

    protected $fillable = [
        'nama', 
        'jenjang', 
        'akreditasi', 
        'kuota', 
        'biaya',
        'icon'
    ];
    // Relasi ke Komponen Biaya (Setiap prodi punya 1 set biaya)
    public function komponenBiaya()
    {
        return $this->hasOne(KomponenBiaya::class, 'prodi_id');
    }
}