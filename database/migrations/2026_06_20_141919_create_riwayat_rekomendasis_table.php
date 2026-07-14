<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_rekomendasis', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable();
            $table->string('jurusan_diminati')->nullable();

            $table->float('skor_logika')->default(0);
            $table->float('skor_sosial')->default(0);
            $table->float('skor_kreatif')->default(0);
            $table->float('skor_bisnis')->default(0);
            $table->float('skor_sains')->default(0);
            $table->float('skor_komunikatif')->default(0);
            $table->float('skor_teliti')->default(0);
            $table->float('skor_empati')->default(0);
            $table->float('skor_kepemimpinan')->default(0);

            $table->string('hasil_rekomendasi_ai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_rekomendasis');
    }
};