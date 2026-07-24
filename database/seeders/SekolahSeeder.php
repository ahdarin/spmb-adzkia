<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Seeder data sekolah dari file CSV resmi DAPODIK / referensi.data.kemdikbud.go.id.
 *
 * CARA PAKAI:
 *   1. Taruh file CSV di: database/seeders/data/Data Induk Satuan Pendidikan  - DAFTAR Nasional 360 - dikmen - ASC - 20 Juli 2026.csv
 *   2. Jalankan: php artisan db:seed --class="Database\Seeders\SekolahSeeder"
 *
 * Format CSV yang didukung (header wajib ada, urutan kolom bebas):
 *   NPSN,Nama,Bentuk,Jenis,Status,Jenjang,Kabupaten,Kecamatan,Kelurahan,Alamat,Jalur,Pembina
 *
 * Baris yang diawali "#" (metadata seperti "# Wilayah::" / "# Bentuk::") otomatis dilewati.
 *
 * Catatan: kolom "Provinsi" TIDAK ada di CSV sumber (hanya Kabupaten), jadi
 * kolom provinsi di database akan kosong (null) untuk data yang diimport
 * lewat cara ini. Ini aman karena kolom provinsi memang nullable.
 *
 * Data diproses per-chunk (500 baris) pakai upsert supaya cepat untuk file besar,
 * dan otomatis update kalau NPSN sudah ada di database (tidak duplikat).
 */
class SekolahSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/Data Induk Satuan Pendidikan  - DAFTAR Nasional 360 - dikmen - ASC - 20 Juli 2026.csv');

        if (!file_exists($path)) {
            $this->command->error("File CSV tidak ditemukan di: {$path}");
            $this->command->warn('Pastikan file sudah diletakkan di database/seeders/data');
            return;
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            $this->command->error('Gagal membuka file CSV.');
            return;
        }

        $header       = null;
        $columnIndex  = [];
        $buffer       = [];
        $totalDiimpor = 0;
        $chunkSize    = 500;

        while (($row = fgetcsv($handle)) !== false) {
            // Lewati baris kosong atau metadata (# Wilayah::, # Bentuk::, dst)
            if (empty($row) || !isset($row[0]) || str_starts_with(trim($row[0]), '#')) {
                continue;
            }

            // Baris pertama yang bukan metadata = header
            if ($header === null) {
                $header = array_map('trim', $row);
                foreach ($header as $i => $kolom) {
                    $columnIndex[strtolower($kolom)] = $i;
                }
                continue;
            }

            // Ambil kolom berdasarkan nama header (bukan posisi tetap, lebih aman)
            $npsn  = trim($row[$columnIndex['npsn']]   ?? '');
            $nama  = trim($row[$columnIndex['nama']]   ?? '');

            if ($npsn === '' || $nama === '') {
                continue; // baris rusak/tidak lengkap, lewati
            }

            $status = trim($row[$columnIndex['status']] ?? '');
            $status = match (strtoupper($status)) {
                'NEGERI' => 'Negeri',
                'SWASTA' => 'Swasta',
                default  => $status ?: null,
            };

            $buffer[] = [
                'npsn'         => $npsn,
                'nama_sekolah' => $nama,
                'bentuk'       => trim($row[$columnIndex['bentuk']]     ?? '') ?: null,
                'status'       => $status,
                'kota'         => trim($row[$columnIndex['kabupaten']]  ?? '') ?: null,
                'alamat'       => trim($row[$columnIndex['alamat']]     ?? '') ?: null,
                'provinsi'     => null, // tidak tersedia di CSV sumber
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ];

            // Insert per-chunk supaya tidak boros memori untuk file besar
            if (count($buffer) >= $chunkSize) {
                $this->upsertChunk($buffer);
                $totalDiimpor += count($buffer);
                $this->command->info("Diproses: {$totalDiimpor} baris...");
                $buffer = [];
            }
        }

        // Sisa buffer terakhir yang belum genap satu chunk
        if (!empty($buffer)) {
            $this->upsertChunk($buffer);
            $totalDiimpor += count($buffer);
        }

        fclose($handle);

        $this->command->info("✅ Selesai. Total {$totalDiimpor} data sekolah diimport/diperbarui dari CSV.");
    }

    /**
     * Upsert satu batch data sekolah berdasarkan kolom unique "npsn".
     * Kalau NPSN sudah ada → kolom yang disebut di argumen ke-3 akan diupdate.
     * Kalau belum ada → insert baru.
     */
    private function upsertChunk(array $rows): void
    {
        DB::table('sekolahs')->upsert(
            $rows,
            ['npsn'], // kolom unique untuk deteksi duplikat
            ['nama_sekolah', 'bentuk', 'status', 'kota', 'alamat', 'updated_at'] // kolom yang diupdate kalau sudah ada
        );
    }
}