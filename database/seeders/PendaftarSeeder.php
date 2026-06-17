<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DataPendaftar as Pendaftar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PendaftarSeeder extends Seeder
{
    public function run(): void
    {
        
        $user1 = User::create([
            'name' => 'Fathir Al-Fatih',
            'email' => 'fathir@example.com',
            'password' => Hash::make('password'),
        ]);
        Pendaftar::create([
            'user_id' => $user1->id,
            'no_hp' => '081122334455',
            'program_studi' => 'Teknik Informatika',
            'jalur' => 'Mandiri',
            'jalur_detail' => 'Gelombang 1',
            'status' => 'validasi', 
        ]);

        
        $user2 = User::create([
            'name' => 'Anindya Putri',
            'email' => 'anindya@example.com',
            'password' => Hash::make('password'),
        ]);
        Pendaftar::create([
            'user_id' => $user2->id,
            'no_hp' => '085566778899',
            'program_studi' => 'Sistem Informasi',
            'jalur' => 'Prestasi',
            'jalur_detail' => 'Tahfidz',
            'status' => 'terverifikasi', 
        ]);

        
        $user3 = User::create([
            'name' => 'Gibran Rakabuming',
            'email' => 'gibran@example.com',
            'password' => Hash::make('password'),
        ]);
        Pendaftar::create([
            'user_id' => $user3->id,
            'no_hp' => '089900112233',
            'program_studi' => 'Pendidikan Agama Islam',
            'jalur' => 'Reguler',
            'jalur_detail' => 'Gelombang 2',
            'status' => 'Belum Bayar', 
        ]);
    }
}