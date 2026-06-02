<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->string('tahun_akademik')->default('2024/2025');
        $table->string('akreditasi')->default('B');
        $table->string('video_profil')->default('q-r5HNQrCG0');
        $table->string('brosur_path')->nullable();
        $table->text('alamat')->nullable();
        $table->string('telepon')->nullable();
        $table->string('email')->nullable();
        $table->text('link_maps')->nullable();
        
        // --- TAMBAHAN BARU UNTUK TAB GELOMBANG & MAINTENANCE ---
        $table->boolean('maintenance_mode')->default(false);
        $table->boolean('pendaftaran_aktif')->default(true);
        $table->date('gelombang_1_buka')->nullable();
        $table->date('gelombang_1_tutup')->nullable();
        $table->date('gelombang_2_buka')->nullable();
        $table->date('gelombang_2_tutup')->nullable();
        $table->date('gelombang_2_buka')->nullable();
        $table->date('gelombang_2_tutup')->nullable();        
        $table->date('gelombang_3_buka')->nullable();
        $table->date('gelombang_3_tutup')->nullable();
        $table->timestamps();
    });
}};