<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('data_pendaftars', function (Blueprint $table) {
        $table->string('sekolah_asal')->nullable();
        $table->string('tahun_lulus')->nullable();
        $table->decimal('nilai_akhir', 5, 2)->nullable(); 
        $table->string('gender')->nullable();
        $table->date('tanggal_lahir')->nullable();
    });
}

public function down(): void
{
    Schema::table('data_pendaftars', function (Blueprint $table) {
        $table->dropColumn(['sekolah_asal', 'tahun_lulus', 'nilai_akhir', 'gender', 'tanggal_lahir']);
    });
}
};
