<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gelombangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_gelombang');
            $table->year('tahun');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->unsignedTinyInteger('jumlah_jalur_dibuka')->default(1)->comment('Berapa jalur yang dibuka di gelombang ini');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gelombangs');
    }
};