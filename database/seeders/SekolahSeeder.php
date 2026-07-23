<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use Illuminate\Database\Seeder;

/**
 * Seeder data sekolah STARTER.
 *
 * Berisi data sekolah negeri di Kota Padang & sekitarnya yang datanya sudah
 * saya cek silang ke sumber resmi (dapo.kemendikdasmen.go.id /
 * referensi.data.kemdikbud.go.id) supaya NPSN-nya akurat — bukan hasil
 * tebakan. Ini dipakai sebagai data awal saja; sisanya akan terisi otomatis
 * lewat fitur "cari NPSN" di form pendaftar (lihat resolveSekolah() di
 * DashboardUserController) setiap kali mahasiswa daftar dengan sekolah yang
 * belum ada di master.
 *
 * CARA PAKAI:
 *   php artisan db:seed --class="Database\Seeders\SekolahSeeder"
 *
 * Silakan tambah baris sendiri untuk sekolah lain yang sering muncul di
 * pendaftar kamu (cek manual NPSN-nya di referensi.data.kemdikbud.go.id
 * biar akurat, jangan asal isi).
 */
class SekolahSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // ── SMA Negeri Kota Padang ──────────────────────────────
            ['npsn' => '10303461', 'nama_sekolah' => 'SMA Negeri 1 Padang', 'kota' => 'Kota Padang', 'provinsi' => 'Sumatera Barat', 'bentuk' => 'SMA', 'status' => 'Negeri'],

            // ── SMK Negeri Kota Padang ───────────────────────────────
            ['npsn' => '10304847', 'nama_sekolah' => 'SMK Negeri 1 Padang', 'kota' => 'Kota Padang', 'provinsi' => 'Sumatera Barat', 'bentuk' => 'SMK', 'status' => 'Negeri'],
            ['npsn' => '10304848', 'nama_sekolah' => 'SMK Negeri 2 Padang', 'kota' => 'Kota Padang', 'provinsi' => 'Sumatera Barat', 'bentuk' => 'SMK', 'status' => 'Negeri'],
            ['npsn' => '10304849', 'nama_sekolah' => 'SMK Negeri 3 Padang', 'kota' => 'Kota Padang', 'provinsi' => 'Sumatera Barat', 'bentuk' => 'SMK', 'status' => 'Negeri'],
            ['npsn' => '10304850', 'nama_sekolah' => 'SMK Negeri 4 Padang', 'kota' => 'Kota Padang', 'provinsi' => 'Sumatera Barat', 'bentuk' => 'SMK', 'status' => 'Negeri'],
            ['npsn' => '10304851', 'nama_sekolah' => 'SMK Negeri 5 Padang', 'kota' => 'Kota Padang', 'provinsi' => 'Sumatera Barat', 'bentuk' => 'SMK', 'status' => 'Negeri'],
            ['npsn' => '10303507', 'nama_sekolah' => 'SMK Negeri 6 Padang', 'kota' => 'Kota Padang', 'provinsi' => 'Sumatera Barat', 'bentuk' => 'SMK', 'status' => 'Negeri'],
            ['npsn' => '10304188', 'nama_sekolah' => 'SMK Negeri 7 Padang', 'kota' => 'Kota Padang', 'provinsi' => 'Sumatera Barat', 'bentuk' => 'SMK', 'status' => 'Negeri'],
            ['npsn' => '10304852', 'nama_sekolah' => 'SMK Negeri 8 Padang', 'kota' => 'Kota Padang', 'provinsi' => 'Sumatera Barat', 'bentuk' => 'SMK', 'status' => 'Negeri'],
            ['npsn' => '10304853', 'nama_sekolah' => 'SMK Negeri 9 Padang', 'kota' => 'Kota Padang', 'provinsi' => 'Sumatera Barat', 'bentuk' => 'SMK', 'status' => 'Negeri'],

            // ── SMA Negeri kota/kabupaten sekitar Padang ────────────
            ['npsn' => '10303611', 'nama_sekolah' => 'SMA Negeri 1 Padang Panjang', 'kota' => 'Kota Padang Panjang', 'provinsi' => 'Sumatera Barat', 'bentuk' => 'SMA', 'status' => 'Negeri'],
            ['npsn' => '10305563', 'nama_sekolah' => 'SMA Negeri 1 Padang Sago', 'kota' => 'Kabupaten Padang Pariaman', 'provinsi' => 'Sumatera Barat', 'bentuk' => 'SMA', 'status' => 'Negeri'],
        ];

        foreach ($data as $row) {
            Sekolah::updateOrCreate(['npsn' => $row['npsn']], $row);
        }

        $this->command->info('Selesai. ' . count($data) . ' data sekolah starter diimport/diperbarui.');
        $this->command->info('Sisanya akan terisi otomatis lewat form pendaftaran mahasiswa (isi NPSN saat daftar).');
    }
}
