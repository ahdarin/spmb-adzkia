<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jalur;

class JalurSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama agar tidak duplikat
        Jalur::truncate();

        $jalurs = [
            // ── JALUR UMUM ────────────────────────────────────────────
            [
                'nama_jalur'          => 'Reguler',
                'kode_nim'            => 'REG',
                'tipe_jalur'          => 'Reguler',
                'is_free_registration'=> false,
                'has_exam'            => false,
                'is_active'           => true,
                'dokumen_syarat'      => json_encode([
                    'Ijazah / SKL', 'Kartu Tanda Penduduk (KTP)', 'Pas Foto 3x4',
                ]),
            ],
            [
                'nama_jalur'          => 'Beasiswa Adzkia Unggul (BAU)',
                'kode_nim'            => 'BAU',
                'tipe_jalur'          => 'Beasiswa',
                'is_free_registration'=> true,
                'has_exam'            => true,
                'is_active'           => true,
                'dokumen_syarat'      => json_encode([
                    'Ijazah / SKL', 'Kartu Tanda Penduduk (KTP)', 'Pas Foto 3x4',
                    'Sertifikat Prestasi (jika ada)',
                ]),
            ],
            [
                'nama_jalur'          => 'Beasiswa PMDK',
                'kode_nim'            => 'PMD',
                'tipe_jalur'          => 'Beasiswa',
                'is_free_registration'=> true,
                'has_exam'            => false,
                'is_active'           => true,
                'dokumen_syarat'      => json_encode([
                    'Ijazah / SKL', 'Kartu Tanda Penduduk (KTP)', 'Pas Foto 3x4',
                    'Raport Semester 1-5',
                ]),
            ],
            [
                'nama_jalur'          => 'Beasiswa Prestasi',
                'kode_nim'            => 'PRS',
                'tipe_jalur'          => 'Beasiswa',
                'is_free_registration'=> true,
                'has_exam'            => false,
                'is_active'           => true,
                'dokumen_syarat'      => json_encode([
                    'Ijazah / SKL', 'Kartu Tanda Penduduk (KTP)', 'Pas Foto 3x4',
                    'Sertifikat Prestasi Tingkat Nasional / Regional',
                ]),
            ],
            [
                'nama_jalur'          => 'Beasiswa KIP-K',
                'kode_nim'            => 'KIP',
                'tipe_jalur'          => 'Beasiswa',
                'is_free_registration'=> true,
                'has_exam'            => false,
                'is_active'           => true,
                'dokumen_syarat'      => json_encode([
                    'Ijazah / SKL', 'Kartu Tanda Penduduk (KTP)', 'Pas Foto 3x4',
                    'Kartu KIP / KKS / PKH', 'Surat Keterangan Tidak Mampu (SKTM)',
                ]),
            ],
            [
                'nama_jalur'          => 'Spirit Sarjana 1',
                'kode_nim'            => 'SS1',
                'tipe_jalur'          => 'Beasiswa',
                'is_free_registration'=> false,
                'has_exam'            => false,
                'is_active'           => true,
                'dokumen_syarat'      => json_encode([
                    'Ijazah / SKL', 'Kartu Tanda Penduduk (KTP)', 'Pas Foto 3x4',
                ]),
            ],
            [
                'nama_jalur'          => 'Spirit Sarjana 2',
                'kode_nim'            => 'SS2',
                'tipe_jalur'          => 'Beasiswa',
                'is_free_registration'=> false,
                'has_exam'            => false,
                'is_active'           => true,
                'dokumen_syarat'      => json_encode([
                    'Ijazah / SKL', 'Kartu Tanda Penduduk (KTP)', 'Pas Foto 3x4',
                ]),
            ],
            [
                'nama_jalur'          => 'Transfer Lanjut',
                'kode_nim'            => 'TRF',
                'tipe_jalur'          => 'Transfer Lanjut',
                'is_free_registration'=> false,
                'has_exam'            => false,
                'is_active'           => true,
                'dokumen_syarat'      => json_encode([
                    'Ijazah D3 / Transkrip Nilai', 'Kartu Tanda Penduduk (KTP)',
                    'Pas Foto 3x4', 'Surat Keterangan Pindah dari Kampus Asal',
                ]),
            ],

            // ── JALUR RPL ─────────────────────────────────────────────
            [
                'nama_jalur'          => 'RPL - Afirmasi YASB',
                'kode_nim'            => 'RPL-YASB',
                'tipe_jalur'          => 'RPL',
                'is_free_registration'=> false,
                'has_exam'            => false,
                'is_active'           => true,
                'dokumen_syarat'      => json_encode([
                    'Ijazah SMA / SMK / D3', 'Kartu Tanda Penduduk (KTP)', 'Pas Foto 3x4',
                    'Surat Rekomendasi YASB', 'Portofolio / Pengalaman Kerja',
                ]),
            ],
            [
                'nama_jalur'          => 'RPL - Afirmasi JSIT',
                'kode_nim'            => 'RPL-JSIT',
                'tipe_jalur'          => 'RPL',
                'is_free_registration'=> false,
                'has_exam'            => false,
                'is_active'           => true,
                'dokumen_syarat'      => json_encode([
                    'Ijazah SMA / SMK / D3', 'Kartu Tanda Penduduk (KTP)', 'Pas Foto 3x4',
                    'Surat Rekomendasi JSIT', 'Portofolio / Pengalaman Kerja',
                ]),
            ],
            [
                'nama_jalur'          => 'RPL - Afirmasi GTK',
                'kode_nim'            => 'RPL-GTK',
                'tipe_jalur'          => 'RPL',
                'is_free_registration'=> false,
                'has_exam'            => false,
                'is_active'           => true,
                'dokumen_syarat'      => json_encode([
                    'Ijazah SMA / SMK / D3', 'Kartu Tanda Penduduk (KTP)', 'Pas Foto 3x4',
                    'Surat Keterangan sebagai Guru / Tenaga Kependidikan',
                    'SK Pengangkatan / Kontrak Kerja',
                ]),
            ],
            [
                'nama_jalur'          => 'RPL - Kelas Khusus',
                'kode_nim'            => 'RPL-KK',
                'tipe_jalur'          => 'RPL',
                'is_free_registration'=> false,
                'has_exam'            => false,
                'is_active'           => true,
                'dokumen_syarat'      => json_encode([
                    'Ijazah SMA / SMK / D3', 'Kartu Tanda Penduduk (KTP)', 'Pas Foto 3x4',
                    'Portofolio / Sertifikat Kompetensi',
                ]),
            ],
            [
                'nama_jalur'          => 'RPL - Kelas Reguler',
                'kode_nim'            => 'RPL-REG',
                'tipe_jalur'          => 'RPL',
                'is_free_registration'=> false,
                'has_exam'            => false,
                'is_active'           => true,
                'dokumen_syarat'      => json_encode([
                    'Ijazah SMA / SMK / D3', 'Kartu Tanda Penduduk (KTP)', 'Pas Foto 3x4',
                    'Portofolio / Pengalaman Kerja',
                ]),
            ],

            // ── JALUR MITRA NAGARI ────────────────────────────────────
            [
                'nama_jalur'          => 'Mitra Nagari - Reguler',
                'kode_nim'            => 'MN-REG',
                'tipe_jalur'          => 'Mitra Nagari',
                'is_free_registration'=> false,
                'has_exam'            => false,
                'is_active'           => true,
                'dokumen_syarat'      => json_encode([
                    'Ijazah / SKL', 'Kartu Tanda Penduduk (KTP)', 'Pas Foto 3x4',
                    'Surat Rekomendasi dari Wali Nagari',
                ]),
            ],
            [
                'nama_jalur'          => 'Mitra Nagari - Spirit Sarjana 1',
                'kode_nim'            => 'MN-SS1',
                'tipe_jalur'          => 'Mitra Nagari',
                'is_free_registration'=> false,
                'has_exam'            => false,
                'is_active'           => true,
                'dokumen_syarat'      => json_encode([
                    'Ijazah / SKL', 'Kartu Tanda Penduduk (KTP)', 'Pas Foto 3x4',
                    'Surat Rekomendasi dari Wali Nagari',
                ]),
            ],
            [
                'nama_jalur'          => 'Mitra Nagari - Spirit Sarjana 2',
                'kode_nim'            => 'MN-SS2',
                'tipe_jalur'          => 'Mitra Nagari',
                'is_free_registration'=> false,
                'has_exam'            => false,
                'is_active'           => true,
                'dokumen_syarat'      => json_encode([
                    'Ijazah / SKL', 'Kartu Tanda Penduduk (KTP)', 'Pas Foto 3x4',
                    'Surat Rekomendasi dari Wali Nagari',
                ]),
            ],
        ];

        foreach ($jalurs as $jalur) {
            Jalur::create($jalur);
        }

        $this->command->info('✅ ' . count($jalurs) . ' data jalur pendaftaran berhasil di-seed!');
        $this->command->table(
            ['Nama Jalur', 'Kode NIM', 'Tipe', 'Gratis', 'Ada Ujian'],
            collect($jalurs)->map(fn($j) => [
                $j['nama_jalur'],
                $j['kode_nim'],
                $j['tipe_jalur'],
                $j['is_free_registration'] ? '✓' : '—',
                $j['has_exam']             ? '✓' : '—',
            ])->toArray()
        );
    }
}