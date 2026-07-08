<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gelombang;

class GelombangSeeder extends Seeder
{
    public function run(): void
    {
        $gelombangs = [
            [
                'nama_gelombang'      => 'Gelombang 1',
                'tahun'               => 2026,
                'tanggal_mulai'       => '2026-01-01',
                'tanggal_selesai'     => '2026-03-31',
                'jumlah_jalur_dibuka' => 8,
                'is_active'           => false,
            ],
            [
                'nama_gelombang'      => 'Gelombang 2',
                'tahun'               => 2026,
                'tanggal_mulai'       => '2026-04-01',
                'tanggal_selesai'     => '2026-06-30',
                'jumlah_jalur_dibuka' => 8,
                'is_active'           => false,
            ],
            [
                'nama_gelombang'      => 'Gelombang 3',
                'tahun'               => 2026,
                'tanggal_mulai'       => '2026-07-01',
                'tanggal_selesai'     => '2026-09-30',
                'jumlah_jalur_dibuka' => 8,
                'is_active'           => true,
            ],
        ];

        foreach ($gelombangs as $g) {
            Gelombang::updateOrCreate(
                ['nama_gelombang' => $g['nama_gelombang'], 'tahun' => $g['tahun']],
                $g
            );
        }
    }
}