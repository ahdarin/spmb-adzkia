<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prodi;

class ProdiSeeder extends Seeder
{
    public function run()
    {
        $prodis = [
            [
                'nama' => 'Pendidikan Dasar',
                'jenjang' => 'S2',
                'akreditasi' => 'Baik',
                'kuota' => 0,
                'biaya' => 0,
                'deskripsi' => '',
                'icon' => 'book-open',
            ],
            [
                'nama' => 'Pendidikan Guru Sekolah Dasar (PGSD)',
                'jenjang' => 'S1',
                'akreditasi' => 'B',
                'kuota' => 0,
                'biaya' => 0,
                'deskripsi' => '',
                'icon' => 'book',
            ],
            [
                'nama' => 'Pendidikan Guru Pendidikan Anak Usia Dini (PGPAUD)',
                'jenjang' => 'S1',
                'akreditasi' => 'B',
                'kuota' => 0,
                'biaya' => 0,
                'deskripsi' => '',
                'icon' => 'book-open',
            ],
            [
                'nama' => 'Pendidikan Bahasa Indonesia (PBI)',
                'jenjang' => 'S1',
                'akreditasi' => 'Baik',
                'kuota' => 0,
                'biaya' => 0,
                'deskripsi' => '',
                'icon' => 'book',
            ],
            [
                'nama' => 'Pendidikan Fisika',
                'jenjang' => 'S1',
                'akreditasi' => 'Baik',
                'kuota' => 0,
                'biaya' => 0,
                'deskripsi' => '',
                'icon' => 'pie-chart',
            ],
            [
                'nama' => 'Pendidikan Matematika',
                'jenjang' => 'S1',
                'akreditasi' => 'Baik',
                'kuota' => 0,
                'biaya' => 0,
                'deskripsi' => '',
                'icon' => 'bar-chart-2',
            ],
            [
                'nama' => 'Pendidikan Khusus',
                'jenjang' => 'S1',
                'akreditasi' => 'Ter Akreditasi LAMDIK',
                'kuota' => 0,
                'biaya' => 0,
                'deskripsi' => '',
                'icon' => 'users',
            ],
            [
                'nama' => 'Pendidikan Profesi Guru (PPG)',
                'jenjang' => 'Vokasi',
                'akreditasi' => '',
                'kuota' => 0,
                'biaya' => 0,
                'deskripsi' => '',
                'icon' => 'briefcase',
            ],
            [
                'nama' => 'Teknik Industri',
                'jenjang' => 'S1',
                'akreditasi' => 'Baik',
                'kuota' => 0,
                'biaya' => 0,
                'deskripsi' => '',
                'icon' => 'settings',
            ],
            [
                'nama' => 'Sistem Informasi',
                'jenjang' => 'S1',
                'akreditasi' => 'Baik',
                'kuota' => 0,
                'biaya' => 0,
                'deskripsi' => '',
                'icon' => 'code',
            ],
            [
                'nama' => 'Informatika',
                'jenjang' => 'S1',
                'akreditasi' => 'Baik',
                'kuota' => 0,
                'biaya' => 0,
                'deskripsi' => '',
                'icon' => 'cpu',
            ],
            [
                'nama' => 'Kewirausahaan',
                'jenjang' => 'S1',
                'akreditasi' => 'Baik',
                'kuota' => 0,
                'biaya' => 0,
                'deskripsi' => '',
                'icon' => 'dollar-sign',
            ],
            [
                'nama' => 'Agribisnis',
                'jenjang' => 'S1',
                'akreditasi' => 'Baik',
                'kuota' => 0,
                'biaya' => 0,
                'deskripsi' => '',
                'icon' => 'globe',
            ],
            [
                'nama' => 'Manajemen Ritel',
                'jenjang' => 'S1',
                'akreditasi' => 'Baik',
                'kuota' => 0,
                'biaya' => 0,
                'deskripsi' => '',
                'icon' => 'target',
            ],
            [
                'nama' => 'Gizi',
                'jenjang' => 'S1',
                'akreditasi' => 'Baik',
                'kuota' => 0,
                'biaya' => 0,
                'deskripsi' => '',
                'icon' => 'heart',
            ],
            [
                'nama' => 'Hukum Bisnis',
                'jenjang' => 'S1',
                'akreditasi' => 'Baik',
                'kuota' => 0,
                'biaya' => 0,
                'deskripsi' => '',
                'icon' => 'book',
            ],
            [
                'nama' => 'Teknik Sipil',
                'jenjang' => 'S1',
                'akreditasi' => 'Baik',
                'kuota' => 0,
                'biaya' => 0,
                'deskripsi' => '',
                'icon' => 'tool',
            ],
            [
                'nama' => 'Desain Komunikasi Visual',
                'jenjang' => 'S1',
                'akreditasi' => 'Terakreditasi Sementara',
                'kuota' => 0,
                'biaya' => 0,
                'deskripsi' => '',
                'icon' => 'camera',
            ]
            
        ];

        foreach ($prodis as $prodi) {
            Prodi::create($prodi);
        }
    }
}