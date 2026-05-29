<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class DataPendaftar extends Authenticatable
{
    use Notifiable;

    protected $table = 'data_pendaftars';

    // KOSONGKAN GUARDED AGAR SEMUA DATA DARI FORM BISA DISIMPAN
    protected $guarded = [];

    protected $hidden = [
        'password',
    ];

    // Relasi untuk mengambil data pilihan jurusan 1 secara dinamis
    public function prodi1()
    {
        return $this->belongsTo(Prodi::class, 'pilihan_jurusan_1', 'id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
}