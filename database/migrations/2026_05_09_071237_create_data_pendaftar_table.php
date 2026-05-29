<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_pendaftars', function (Blueprint $table) {
            $table->id();
            $table->string('no_pendaftaran')->unique();
            $table->string('jalur_pendaftaran')->default('Reguler'); // Reguler atau Khusus
            $table->string('nama_lengkap');
            $table->string('nik')->unique();
            $table->string('no_whatsapp');
            $table->string('email')->unique();
            $table->string('pilihan_jurusan_1');
            $table->string('pilihan_jurusan_2');
            $table->text('alamat_rumah');
            $table->string('password');
            $table->integer('nominal_biaya')->default(0); // Diatur dinamis lewat nominal admin
            $table->string('status_pembayaran')->default('Belum Bayar');
            $table->string('metode_pembayaran')->nullable(); // Menyimpan metode pembayaran yang dipilih
            $table->text('pesan_revisi')->nullable(); // Untuk menyimpan pesan revisi dari admin jika ada
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_pendaftars');
    }
};