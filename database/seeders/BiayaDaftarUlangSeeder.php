<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BiayaDaftarUlang;
use App\Models\Jalur;
use App\Models\Prodi;
use App\Models\Gelombang;

class BiayaDaftarUlangSeeder extends Seeder
{
    public function run(): void
    {
        $tahun     = 2026;
        $gelombang = Gelombang::where('tahun', $tahun)->where('is_active', true)->first()
                  ?? Gelombang::where('tahun', $tahun)->first();

        if (!$gelombang) {
            $this->command->error('Gelombang tidak ditemukan. Jalankan GelombangSeeder terlebih dahulu.');
            return;
        }

        $prodis = Prodi::all();
        $jalurs = Jalur::all();

        // Biaya per jalur (sama untuk semua prodi dalam jalur yang sama)
        $biayaPerJalur = [
            'REG'      => ['spp' => 3500000, 'sarpras' => 1500000, 'seragam' => 750000],
            'BAU'      => ['spp' => 0,        'sarpras' => 500000,  'seragam' => 750000],
            'PMD'      => ['spp' => 1750000,  'sarpras' => 750000,  'seragam' => 750000],
            'PRS'      => ['spp' => 1750000,  'sarpras' => 750000,  'seragam' => 750000],
            'KIP'      => ['spp' => 0,        'sarpras' => 0,       'seragam' => 500000],
            'RPL-YASB' => ['spp' => 2500000,  'sarpras' => 1000000, 'seragam' => 750000],
            'RPL-JSIT' => ['spp' => 2500000,  'sarpras' => 1000000, 'seragam' => 750000],
            'RPL-KK'   => ['spp' => 3000000,  'sarpras' => 1200000, 'seragam' => 750000],
        ];

        $inserted = 0;

        foreach ($jalurs as $jalur) {
            $biaya = $biayaPerJalur[$jalur->kode_nim] ?? [
                'spp' => 3500000, 'sarpras' => 1500000, 'seragam' => 750000
            ];

            foreach ($prodis as $prodi) {
                BiayaDaftarUlang::updateOrCreate(
                    [
                        'prodi_id'     => $prodi->id,
                        'jalur_id'     => $jalur->id,
                        'gelombang_id' => $gelombang->id,
                        'tahun'        => $tahun,
                    ],
                    [
                        'spp_semester'            => $biaya['spp'],
                        'biaya_sarpras'           => $biaya['sarpras'],
                        'biaya_seragam_orientasi' => $biaya['seragam'],
                        // total_biaya = generated column, tidak di-insert
                    ]
                );
                $inserted++;
            }
        }
    }
}
