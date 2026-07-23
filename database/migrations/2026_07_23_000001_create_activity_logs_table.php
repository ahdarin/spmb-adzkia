<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Siapa pelakunya. Sistem ini punya 2 jenis akun (admin di tabel
            // `users`, pendaftar di tabel `data_pendaftars`) makanya dipisah
            // jadi actor_type + actor_id (bukan foreign key langsung, supaya
            // log tetap ada walau akun terkait dihapus).
            $table->string('actor_type', 20);      // 'admin' | 'pendaftar' | 'system'
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('actor_nama')->nullable();
            $table->string('actor_role')->nullable(); // super_admin, admin/divisi, atau null utk pendaftar

            $table->string('aktivitas');            // kode singkat, mis. "login", "setujui_pembayaran"
            $table->string('modul')->nullable();     // mis. "Pembayaran", "Daftar Ulang", "Master Sekolah"
            $table->text('deskripsi')->nullable();   // kalimat lengkap yang ditampilkan di tabel log

            $table->string('subjek_type')->nullable(); // mis. App\Models\DataPendaftar
            $table->unsignedBigInteger('subjek_id')->nullable(); // id record yang kena aksi

            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();

            $table->index(['actor_type', 'actor_id']);
            $table->index('aktivitas');
            $table->index(['subjek_type', 'subjek_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
