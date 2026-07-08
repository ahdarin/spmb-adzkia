<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jalur;

class JalurSeeder extends Seeder
{
    public function run(): void
    {
        $jalurs = [
            // ─── JALUR REGULER ────────────────────────────────────────────────
            [
                'nama_jalur'           => 'Reguler',
                'kode_nim'             => 'REG',
                'is_free_registration' => false,
                'has_exam'             => false,
                'dokumen_syarat'       => [
                    'Ijazah / SKL',
                    'KTP / Kartu Keluarga',
                    'Pas Foto 4x6 (Background Merah)',
                ],
                'is_active'            => true,
            ],

            // ─── JALUR BEASISWA ───────────────────────────────────────────────
            [
                'nama_jalur'           => 'Beasiswa Adzkia Unggul (BAU)',
                'kode_nim'             => 'BAU',
                'is_free_registration' => true,
                'has_exam'             => true,
                'dokumen_syarat'       => [
                    'Ijazah / SKL',
                    'KTP / Kartu Keluarga',
                    'Pas Foto 4x6 (Background Merah)',
                    'Rapor 3 Semester Terakhir',
                    'Surat Rekomendasi Kepala Sekolah',
                ],
                'is_active'            => true,
            ],
            [
                'nama_jalur'           => 'Beasiswa PMDK',
                'kode_nim'             => 'PMD',
                'is_free_registration' => true,
                'has_exam'             => false,
                'dokumen_syarat'       => [
                    'Ijazah / SKL',
                    'KTP / Kartu Keluarga',
                    'Pas Foto 4x6 (Background Merah)',
                    'Rapor 5 Semester Terakhir',
                    'Surat Rekomendasi Kepala Sekolah',
                ],
                'is_active'            => true,
            ],
            [
                'nama_jalur'           => 'Beasiswa Prestasi',
                'kode_nim'             => 'PRS',
                'is_free_registration' => true,
                'has_exam'             => false,
                'dokumen_syarat'       => [
                    'Ijazah / SKL',
                    'KTP / Kartu Keluarga',
                    'Pas Foto 4x6 (Background Merah)',
                    'Sertifikat / Piagam Prestasi',
                    'Rapor 3 Semester Terakhir',
                ],
                'is_active'            => true,
            ],
            [
                'nama_jalur'           => 'Beasiswa KIP-K',
                'kode_nim'             => 'KIP',
                'is_free_registration' => true,
                'has_exam'             => false,
                'dokumen_syarat'       => [
                    'Ijazah / SKL',
                    'KTP / Kartu Keluarga',
                    'Pas Foto 4x6 (Background Merah)',
                    'Kartu KIP / SKTM',
                    'Rapor 3 Semester Terakhir',
                    'Surat Pernyataan Tidak Mampu',
                ],
                'is_active'            => true,
            ],

            // ─── JALUR RPL ────────────────────────────────────────────────────
            [
                'nama_jalur'           => 'RPL Afirmasi YASB',
                'kode_nim'             => 'RPL-YASB',
                'is_free_registration' => true,
                'has_exam'             => false,
                'dokumen_syarat'       => [
                    'Ijazah / SKL',
                    'KTP / Kartu Keluarga',
                    'Pas Foto 4x6 (Background Merah)',
                    'Surat Keterangan dari YASB',
                    'Transkrip Nilai / Portofolio',
                ],
                'is_active'            => true,
            ],
            [
                'nama_jalur'           => 'RPL Afirmasi JSIT',
                'kode_nim'             => 'RPL-JSIT',
                'is_free_registration' => true,
                'has_exam'             => false,
                'dokumen_syarat'       => [
                    'Ijazah / SKL',
                    'KTP / Kartu Keluarga',
                    'Pas Foto 4x6 (Background Merah)',
                    'Surat Keterangan dari JSIT',
                    'Transkrip Nilai / Portofolio',
                ],
                'is_active'            => true,
            ],
            [
                'nama_jalur'           => 'RPL Kelas Khusus',
                'kode_nim'             => 'RPL-KK',
                'is_free_registration' => false,
                'has_exam'             => true,
                'dokumen_syarat'       => [
                    'Ijazah / SKL',
                    'KTP / Kartu Keluarga',
                    'Pas Foto 4x6 (Background Merah)',
                    'Transkrip Nilai / Portofolio',
                    'Surat Pernyataan Kesanggupan',
                ],
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