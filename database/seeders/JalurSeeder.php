<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jalur;

class JalurSeeder extends Seeder
{
    public function run(): void
    {
        $jalurs = [
            [
                'nama_jalur'           => 'Reguler',
                'kode_nim'             => 'REG',
                'is_free_registration' => false,
                'has_exam'             => false,
                'dokumen_syarat'       => ['Ijazah/SKL', 'KTP/KK', 'Pas Foto 4x6'],
                'is_active'            => true,
            ],
            [
                'nama_jalur'           => 'Beasiswa',
                'kode_nim'             => 'BEA',
                'is_free_registration' => true,
                'has_exam'             => true,
                'dokumen_syarat'       => ['Ijazah/SKL', 'KTP/KK', 'Pas Foto 4x6', 'Surat Keterangan Tidak Mampu', 'Rapor 3 Semester Terakhir'],
                'is_active'            => true,
            ],
            [
                'nama_jalur'           => 'Prestasi',
                'kode_nim'             => 'PRE',
                'is_free_registration' => false,
                'has_exam'             => false,
                'dokumen_syarat'       => ['Ijazah/SKL', 'KTP/KK', 'Pas Foto 4x6', 'Sertifikat Prestasi'],
                'is_active'            => true,
            ],
        ];

        foreach ($jalurs as $jalur) {
            Jalur::updateOrCreate(
                ['kode_nim' => $jalur['kode_nim']],
                $jalur
            );
        }
    }
}
