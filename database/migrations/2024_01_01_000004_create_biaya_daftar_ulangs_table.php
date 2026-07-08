<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biaya_daftar_ulangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodi_id')->constrained('prodis')->cascadeOnDelete();
            $table->foreignId('jalur_id')->constrained('jalurs')->cascadeOnDelete();
            $table->foreignId('gelombang_id')->constrained('gelombangs')->cascadeOnDelete();
            $table->year('tahun');
            $table->unsignedBigInteger('spp_semester')->default(0)->comment('Biaya SPP per semester');
            $table->unsignedBigInteger('biaya_sarpras')->default(0)->comment('Biaya sarana prasarana');
            $table->unsignedBigInteger('biaya_seragam_orientasi')->default(0)->comment('Biaya seragam dan orientasi');
            $table->unsignedBigInteger('total_biaya')->storedAs('spp_semester + biaya_sarpras + biaya_seragam_orientasi')->comment('Dikalkulasi otomatis oleh database');
            $table->timestamps();

            // Satu kombinasi prodi+jalur+gelombang+tahun harus unik
            $table->unique(['prodi_id', 'jalur_id', 'gelombang_id', 'tahun'], 'biaya_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biaya_daftar_ulangs');
    }
};
