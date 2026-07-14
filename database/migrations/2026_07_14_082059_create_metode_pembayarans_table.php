<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metode_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('kategori'); // 'Bank Transfer', 'Virtual Account', 'E-Wallet'
            $table->string('nama_provider'); // 'MANDIRI', 'BNI', 'DANA', 'OVO'
            $table->string('nama_bank_lengkap')->nullable();
            $table->string('nomor_tujuan');
            $table->string('atas_nama');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metode_pembayarans');
    }
};