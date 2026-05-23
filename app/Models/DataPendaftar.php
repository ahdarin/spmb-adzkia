<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class DataPendaftar extends Authenticatable
{
    use Notifiable;

    protected $table = 'data_pendaftars';

    protected $fillable = [
        'no_pendaftaran',
        'jalur_pendaftaran',
        'nama_lengkap',
        'nik',
        'no_whatsapp',
        'email',
        'pilihan_jurusan_1',
        'pilihan_jurusan_2',
        'alamat_rumah',
        'password',
        'nominal_biaya',
        'status_pembayaran',
        'metode_pembayaran', 
        'bukti_bayar',
        'pas_foto',      
        'scan_ktp',      
        'ijazah_skl',
        'sekolah_asal',
        'tahun_lulus',
        'nilai_akhir',
        'gender',
        'tanggal_lahir',
        'agama', 
        'tempat_lahir', 
        'provinsi', 
        'kota_kabupaten', 
        'jurusan_sma',
    ];

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