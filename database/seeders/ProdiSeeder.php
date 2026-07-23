<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prodi;

class ProdiSeeder extends Seeder
{
    public function run()
    {
        $prodis = [
            ['nama' => 'Pendidikan Guru Sekolah Dasar (PGSD)',            'jenjang' => 'S1',    'akreditasi' => 'B',          'kuota' => 0, 'biaya' => 0, 'icon' => 'book'],
            ['nama' => 'Pendidikan Guru Pendidikan Anak Usia Dini (PGPAUD)', 'jenjang' => 'S1', 'akreditasi' => 'B',          'kuota' => 0, 'biaya' => 0, 'icon' => 'book-open'],
            ['nama' => 'Pendidikan Bahasa Indonesia (PBI)',                'jenjang' => 'S1',    'akreditasi' => 'Baik',       'kuota' => 0, 'biaya' => 0, 'icon' => 'book'],
            ['nama' => 'Pendidikan Fisika',                               'jenjang' => 'S1',    'akreditasi' => 'Baik',       'kuota' => 0, 'biaya' => 0, 'icon' => 'pie-chart'],
            ['nama' => 'Pendidikan Matematika',                           'jenjang' => 'S1',    'akreditasi' => 'Baik',       'kuota' => 0, 'biaya' => 0, 'icon' => 'bar-chart-2'],
            ['nama' => 'Pendidikan Khusus',                               'jenjang' => 'S1',    'akreditasi' => 'Baik',       'kuota' => 0, 'biaya' => 0, 'icon' => 'users'],
            ['nama' => 'Teknik Industri',                                 'jenjang' => 'S1',    'akreditasi' => 'Baik',       'kuota' => 0, 'biaya' => 0, 'icon' => 'settings'],
            ['nama' => 'Sistem Informasi',                                'jenjang' => 'S1',    'akreditasi' => 'Baik',       'kuota' => 0, 'biaya' => 0, 'icon' => 'code'],
            ['nama' => 'Informatika',                                     'jenjang' => 'S1',    'akreditasi' => 'Baik',       'kuota' => 0, 'biaya' => 0, 'icon' => 'cpu'],
            ['nama' => 'Kewirausahaan',                                   'jenjang' => 'S1',    'akreditasi' => 'Baik',       'kuota' => 0, 'biaya' => 0, 'icon' => 'dollar-sign'],
            ['nama' => 'Agribisnis',                                      'jenjang' => 'S1',    'akreditasi' => 'Baik',       'kuota' => 0, 'biaya' => 0, 'icon' => 'globe'],
            ['nama' => 'Manajemen Ritel',                                 'jenjang' => 'S1',    'akreditasi' => 'Baik',       'kuota' => 0, 'biaya' => 0, 'icon' => 'target'],
            ['nama' => 'Gizi',                                            'jenjang' => 'S1',    'akreditasi' => 'Baik',       'kuota' => 0, 'biaya' => 0, 'icon' => 'heart'],
            ['nama' => 'Hukum Bisnis',                                    'jenjang' => 'S1',    'akreditasi' => 'Baik',       'kuota' => 0, 'biaya' => 0, 'icon' => 'book'],
            ['nama' => 'Teknik Sipil',                                    'jenjang' => 'S1',    'akreditasi' => 'Baik',       'kuota' => 0, 'biaya' => 0, 'icon' => 'tool'],
            ['nama' => 'Desain Komunikasi Visual',                        'jenjang' => 'S1',    'akreditasi' => 'Baik',       'kuota' => 0, 'biaya' => 0, 'icon' => 'camera'],
            ['nama' => 'Pendidikan Dasar',                                'jenjang' => 'S2',    'akreditasi' => 'Baik',       'kuota' => 0, 'biaya' => 0, 'icon' => 'book-open'],
            ['nama' => 'Pendidikan Profesi Guru (PPG)',                   'jenjang' => 'Profesi', 'akreditasi' => 'Baik',     'kuota' => 0, 'biaya' => 0, 'icon' => 'briefcase'],
        ];

        foreach ($prodis as $prodi) {
            Prodi::updateOrCreate(['nama' => $prodi['nama']], $prodi);
        }
    }
}