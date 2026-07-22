<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah kolom tipe_jalur ke tabel jalurs.
 * Nilai: 'Umum' | 'RPL' | 'Mitra Nagari'
 *
 * Jalankan: php artisan migrate
 * (tidak perlu fresh, tidak menghapus data)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jalurs', function (Blueprint $table) {
            // Tambah setelah kode_nim agar urutan kolom rapi
            $table->string('tipe_jalur', 50)
                  ->default('Umum')
                  ->after('kode_nim')
                  ->comment('Kategori jalur: Umum | RPL | Mitra Nagari');
        });
    }

    public function down(): void
    {
        Schema::table('jalurs', function (Blueprint $table) {
            $table->dropColumn('tipe_jalur');
        });
    }
};
