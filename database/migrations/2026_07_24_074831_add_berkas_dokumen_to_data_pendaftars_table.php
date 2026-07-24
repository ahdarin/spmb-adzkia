<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_pendaftars', function (Blueprint $table) {
            // Menyimpan path dokumen yang diupload secara dinamis, key-nya
            // sesuai nama dokumen di jalurs.dokumen_syarat (mis. "Pas Foto 3x4",
            // "Kartu Tanda Penduduk (KTP)", "Ijazah / SKL"), value-nya path file.
            // Ditaruh setelah kolom npsn_sekolah supaya urutan kolom rapi
            // mengikuti alur pengisian formulir.
            $table->json('berkas_dokumen')->nullable()->after('npsn_sekolah');
        });
    }

    public function down(): void
    {
        Schema::table('data_pendaftars', function (Blueprint $table) {
            $table->dropColumn('berkas_dokumen');
        });
    }
};