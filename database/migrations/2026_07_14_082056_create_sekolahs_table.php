<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sekolahs', function (Blueprint $table) {
            $table->id();
            $table->string('npsn', 20)->unique()->comment('Nomor Pokok Sekolah Nasional');
            $table->string('nama_sekolah');
            $table->string('alamat',   500)->nullable();
            $table->string('kota',     100)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('bentuk',    50)->nullable()->comment('SMA / SMK / MA / dll');
            $table->string('status',    20)->nullable()->comment('Negeri / Swasta');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sekolahs');
    }
};