<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TugasAdmin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TugasAdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan kita punya minimal 1 user/admin untuk diberi tugas
        $admin = User::firstOrCreate(
            ['email' => 'admin_tugas@example.com'],
            [
                'name' => 'Admin Validasi',
                'password' => Hash::make('password')
            ]
        );

        // 2. Buat beberapa tugas sementara
        TugasAdmin::create([
            'admin_id'    => $admin->id,
            'judul_tugas' => 'Validasi Pembayaran Gelombang 1',
            'deskripsi'   => 'Mohon segera cek dan validasi bukti transfer dari 50 pendaftar pertama.',
            'status'      => 'Pending',
        ]);

        TugasAdmin::create([
            'admin_id'    => $admin->id,
            'judul_tugas' => 'Update Pengumuman Kelulusan',
            'deskripsi'   => 'Masukkan data nilai CBT peserta ujian hari ini ke dalam sistem.',
            'status'      => 'In Progress',
        ]);

        TugasAdmin::create([
            'admin_id'    => $admin->id,
            'judul_tugas' => 'Siapkan Soal CBT Baru',
            'deskripsi'   => 'Tolong input 20 soal Logika Matematika tambahan ke bank soal.',
            'status'      => 'Selesai',
        ]);
    }
}