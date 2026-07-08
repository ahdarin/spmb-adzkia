<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_pendaftars', function (Blueprint $table) {
            // Data ortu (Ayah/Ibu/Wali) - diisi khusus saat Daftar Ulang
            if (!Schema::hasColumn('data_pendaftars', 'data_ortu')) {
                $table->json('data_ortu')->nullable();
            }

            // Status alur Daftar Ulang, terpisah dari status_pendaftaran & status_kelulusan
            // Nilai: 'Belum Daftar Ulang', 'Menunggu Validasi', 'Terverifikasi'
            if (!Schema::hasColumn('data_pendaftars', 'status_daftar_ulang')) {
                $table->string('status_daftar_ulang')->default('Belum Daftar Ulang');
            }

            // Bukti transfer pembayaran daftar ulang
            if (!Schema::hasColumn('data_pendaftars', 'bukti_daftar_ulang')) {
                $table->string('bukti_daftar_ulang')->nullable();
            }

            if (!Schema::hasColumn('data_pendaftars', 'metode_daftar_ulang')) {
                $table->string('metode_daftar_ulang')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('data_pendaftars', function (Blueprint $table) {
            $table->dropColumn([
                'data_ortu',
                'status_daftar_ulang',
                'bukti_daftar_ulang',
                'metode_daftar_ulang',
            ]);
        });
    }
};
