<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jalurs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jalur');
            $table->string('kode_nim', 10)->unique()->comment('Prefix kode NIM, misal: REG, BEA, PRE');
            $table->boolean('is_free_registration')->default(false)->comment('Apakah biaya pendaftaran gratis?');
            $table->boolean('has_exam')->default(false)->comment('Apakah jalur ini memiliki ujian seleksi?');
            $table->json('dokumen_syarat')->nullable()->comment('Array daftar dokumen yang wajib diunggah');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jalurs');
    }
};
