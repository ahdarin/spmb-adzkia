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
        // (kolom status_daftar_ulang & bukti_daftar_ulang sudah ditambahkan
        //  oleh migration 000001, jadi tidak perlu diulang di sini)
        if (!Schema::hasColumn('data_pendaftars', 'gelombang_id')) {
            $table->foreignId('gelombang_id')->nullable()->constrained('gelombangs')->onDelete('set null');
        }

        // Output Akhir (NIM)
        if (!Schema::hasColumn('data_pendaftars', 'nim')) {
            $table->string('nim', 50)->nullable()->unique();
        }
    });
}

public function down(): void
{
    Schema::table('data_pendaftars', function (Blueprint $table) {
        if (Schema::hasColumn('data_pendaftars', 'gelombang_id')) {
            $table->dropForeign(['gelombang_id']);
            $table->dropColumn('gelombang_id');
        }

        if (Schema::hasColumn('data_pendaftars', 'nim')) {
            $table->dropColumn('nim');
        }
    });
}
};