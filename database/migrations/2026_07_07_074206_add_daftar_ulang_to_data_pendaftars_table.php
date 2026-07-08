<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('data_pendaftars', function (Blueprint $table) {
        // Relasi ke gelombang saat dia mendaftar
        $table->foreignId('gelombang_id')->nullable()->constrained('gelombangs')->onDelete('set null');
        
        // Status dan Bukti Daftar Ulang
        $table->enum('status_daftar_ulang', ['Belum', 'Menunggu Verifikasi', 'Selesai'])->default('Belum');
        $table->string('bukti_daftar_ulang')->nullable();
        
        // Output Akhir (NIM)
        $table->string('nim', 50)->nullable()->unique();
    });
}

public function down(): void
{
    Schema::table('data_pendaftars', function (Blueprint $table) {
        $table->dropForeign(['gelombang_id']);
        $table->dropColumn(['gelombang_id', 'status_daftar_ulang', 'bukti_daftar_ulang', 'nim']);
    });
}
};
