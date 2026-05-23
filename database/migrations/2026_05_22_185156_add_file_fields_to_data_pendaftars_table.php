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
        $table->string('pas_foto')->nullable();
        $table->string('scan_ktp')->nullable();
        $table->string('ijazah_skl')->nullable();
    });
}

public function down(): void
{
    Schema::table('data_pendaftars', function (Blueprint $table) {
        $table->dropColumn(['pas_foto', 'scan_ktp', 'ijazah_skl']);
    });
}
};
