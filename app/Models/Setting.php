<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
protected $fillable = [
    'tahun_akademik', 'akreditasi', 'video_profil', 'brosur_path', 
    'alamat', 'telepon', 'email', 'link_maps', 'maintenance_mode', 
    'pendaftaran_aktif', 'gelombang_1_buka', 'gelombang_1_tutup', 
    'gelombang_2_buka', 'gelombang_2_tutup', 'gelombang_3_buka', 'gelombang_3_tutup'
];
}