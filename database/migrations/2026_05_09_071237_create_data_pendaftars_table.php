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
            $table->foreignId('jalur_id')->nullable()->constrained('jalurs')->onDelete('set null');
            $table->string('jalur_pendaftaran')->default('Reguler'); // Reguler atau Khusus
            $table->string('nama_lengkap');
            $table->string('nik')->unique();
            $table->string('no_whatsapp');
            $table->string('email')->unique();
            $table->string('pilihan_jurusan_1');
            $table->string('pilihan_jurusan_2');
            $table->text('alamat_rumah');
            $table->string('password');
            $table->integer('nominal_biaya')->default(0);
            $table->string('status_pembayaran')->default('Belum Bayar');
            $table->string('metode_pembayaran')->nullable();
            $table->text('pesan_revisi')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('pas_foto')->nullable();
            $table->string('scan_ktp')->nullable();
            $table->string('ijazah_skl')->nullable();
            $table->string('sekolah_asal')->nullable();
            $table->string('tahun_lulus')->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->string('gender')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('agama')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('kota_kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('jurusan_sma')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->string('status_kelulusan')->nullable();
            $table->string('status_pendaftaran')->nullable()->default('Draft');
            $table->json('data_ortu')->nullable();
            $table->string('status_daftar_ulang')->default('Belum Daftar Ulang');
            $table->string('bukti_daftar_ulang')->nullable();
            $table->string('metode_daftar_ulang')->nullable();
            $table->foreignId('gelombang_id')->nullable()->constrained('gelombangs')->onDelete('set null');
            $table->string('nim', 50)->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_pendaftars');
    }
};