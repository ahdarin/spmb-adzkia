<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('data_pendaftars', function (Blueprint $table) {
            $table->string('agama')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('kota_kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('jurusan_sma')->nullable();
            // tambahkan kolom lain yang error di sini
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_pendaftars', function (Blueprint $table) {
            //
        });
    }
};
