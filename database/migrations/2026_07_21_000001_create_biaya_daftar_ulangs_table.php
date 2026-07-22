<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        // Drop dulu kalau sudah ada (migrate:fresh sudah menangani ini,
        // tapi ditambahkan sebagai pengaman jika dijalankan manual)
        Schema::dropIfExists('biaya_daftar_ulangs');

        Schema::create('biaya_daftar_ulangs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('prodi_id')
                  ->constrained('prodis')
                  ->cascadeOnDelete();

            $table->foreignId('jalur_id')
                  ->constrained('jalurs')
                  ->cascadeOnDelete();

            $table->foreignId('gelombang_id')
                  ->constrained('gelombangs')
                  ->cascadeOnDelete();

            $table->year('tahun')->comment('Tahun akademik, misal: 2026');

            // ─── 3 Komponen Biaya ───────────────────────────────────────
            $table->unsignedBigInteger('spp_semester')->default(0)
                  ->comment('SPP Mahasiswa Baru per semester (Rp)');

            $table->unsignedBigInteger('biaya_sarpras')->default(0)
                  ->comment('Biaya sarana dan prasarana (Rp)');

            $table->unsignedBigInteger('biaya_seragam_orientasi')->default(0)
                  ->comment('Biaya seragam dan orientasi mahasiswa baru (Rp)');

            // ─── Total (dihitung di sisi aplikasi, bukan generated column) ──
            $table->unsignedBigInteger('total_biaya')->default(0)
                  ->comment('Total biaya daftar ulang = spp + sarpras + seragam_orientasi');

            $table->timestamps();

            // Satu record per kombinasi: prodi + jalur + gelombang + tahun
            $table->unique(
                ['prodi_id', 'jalur_id', 'gelombang_id', 'tahun'],
                'biaya_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biaya_daftar_ulangs');
    }
};
