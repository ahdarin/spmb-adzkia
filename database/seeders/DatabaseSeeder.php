<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // 1. Tabel independen — tidak punya foreign key ke tabel manapun
            UserSeeder::class,
            ProdiSeeder::class,
            JalurSeeder::class,
            GelombangSeeder::class,
            PembayaranSeeder::class,
            SoalKuesionerSeeder::class,

            // 2. Bergantung pada Prodi + Jalur + Gelombang di atas
            BiayaDaftarUlangSeeder::class,
        ]);
    }
}