<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BiayaDaftarUlang;
use App\Models\Prodi;
use App\Models\Jalur;
use App\Models\Gelombang;

class BiayaDaftarUlangSeeder extends Seeder
{
    public function run(): void
    {
        $tahun = 2026;

        // Ambil semua gelombang tahun ini
        $gelombangs = Gelombang::where('tahun', $tahun)->orderBy('id')->get();

        if ($gelombangs->isEmpty()) {
            $this->command->error('❌ Gelombang {$tahun} tidak ditemukan. Jalankan GelombangSeeder terlebih dahulu.');
            return;
        }

        $prodis = Prodi::orderBy('id')->get();
        $jalurs = Jalur::orderBy('id')->get();

        if ($prodis->isEmpty() || $jalurs->isEmpty()) {
            $this->command->error('❌ Data Prodi atau Jalur kosong. Jalankan ProdiSeeder dan JalurSeeder terlebih dahulu.');
            return;
        }

        // ─────────────────────────────────────────────────────────────
        // TEMPLATE BIAYA PER JALUR (kode_nim sebagai key)
        //
        // Setiap jalur punya biaya yang SAMA untuk semua prodi.
        // Komponen:
        //   spp      → SPP MABA per semester
        //   sarpras  → Biaya sarana dan prasarana
        //   seragam  → Biaya seragam dan orientasi
        //
        // Sumber: BiayaDaftarUlangSeeder lama + penyesuaian data riil Adzkia
        // ─────────────────────────────────────────────────────────────
        $templatePerJalur = [
            // Jalur Reguler
            'REG'      => ['spp' => 3_500_000, 'sarpras' => 1_500_000, 'seragam' => 750_000],

            // Beasiswa Adzkia Utama (gratis SPP)
            'BAU'      => ['spp' => 0,          'sarpras' =>   500_000, 'seragam' => 750_000],

            // Pindahan (setengah SPP)
            'PMD'      => ['spp' => 1_750_000,  'sarpras' =>   750_000, 'seragam' => 750_000],

            // Prestasi (sama dengan pindahan)
            'PRS'      => ['spp' => 1_750_000,  'sarpras' =>   750_000, 'seragam' => 750_000],

            // KIP Kuliah (fully subsidized)
            'KIP'      => ['spp' => 0,          'sarpras' =>         0, 'seragam' => 500_000],

            // RPL - Afirmasi YASB
            'RPL-YASB' => ['spp' => 2_500_000,  'sarpras' => 1_000_000, 'seragam' => 750_000],

            // RPL - JSIT
            'RPL-JSIT' => ['spp' => 2_500_000,  'sarpras' => 1_000_000, 'seragam' => 750_000],

            // RPL - Kemitraan Khusus
            'RPL-KK'   => ['spp' => 3_000_000,  'sarpras' => 1_200_000, 'seragam' => 750_000],
        ];

        // Fallback jika ada jalur baru yang kode-nya belum ada di template
        $fallback = ['spp' => 3_500_000, 'sarpras' => 1_500_000, 'seragam' => 750_000];

        $total   = 0;
        $dilewati = 0;

        foreach ($gelombangs as $gelombang) {
            foreach ($jalurs as $jalur) {
                // Ambil template biaya berdasarkan kode_nim jalur
                $tmpl = $templatePerJalur[$jalur->kode_nim] ?? $fallback;

                foreach ($prodis as $prodi) {
                    // Untuk S2 (Magister), biaya lebih tinggi
                    $spp     = $prodi->jenjang === 'S2'
                                   ? (int) ($tmpl['spp']    * 1.4)
                                   : $tmpl['spp'];
                    $sarpras = $prodi->jenjang === 'S2'
                                   ? (int) ($tmpl['sarpras'] * 1.2)
                                   : $tmpl['sarpras'];
                    $seragam = $tmpl['seragam'];
                    $total_biaya = $spp + $sarpras + $seragam;

                    try {
                        BiayaDaftarUlang::updateOrCreate(
                            [
                                'prodi_id'     => $prodi->id,
                                'jalur_id'     => $jalur->id,
                                'gelombang_id' => $gelombang->id,
                                'tahun'        => $tahun,
                            ],
                            [
                                'spp_semester'            => $spp,
                                'biaya_sarpras'           => $sarpras,
                                'biaya_seragam_orientasi' => $seragam,
                                'total_biaya'             => $total_biaya,
                            ]
                        );
                        $total++;
                    } catch (\Exception $e) {
                        $this->command->warn("⚠ Lewati: {$prodi->nama} × {$jalur->nama_jalur} × {$gelombang->nama_gelombang} → {$e->getMessage()}");
                        $dilewati++;
                    }
                }
            }
        }

        $this->command->info("✅ Selesai! {$total} data biaya daftar ulang berhasil di-seed ({$dilewati} dilewati).");
        $this->command->table(
            ['Jalur', 'SPP Semester', 'Sarpras', 'Seragam & Orientasi', 'Total'],
            collect($templatePerJalur)->map(function ($t, $kode) {
                $total = $t['spp'] + $t['sarpras'] + $t['seragam'];
                return [
                    $kode,
                    'Rp ' . number_format($t['spp'],    0, ',', '.'),
                    'Rp ' . number_format($t['sarpras'], 0, ',', '.'),
                    'Rp ' . number_format($t['seragam'], 0, ',', '.'),
                    'Rp ' . number_format($total,        0, ',', '.'),
                ];
            })->values()->toArray()
        );
    }
}