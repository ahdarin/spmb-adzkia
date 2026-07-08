<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_pendaftars', function (Blueprint $table) {
            if (!Schema::hasColumn('data_pendaftars', 'jalur_id')) {
                $table->foreignId('jalur_id')
                      ->nullable()
                      ->after('no_pendaftaran')
                      ->constrained('jalurs')
                      ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('data_pendaftars', function (Blueprint $table) {
            if (Schema::hasColumn('data_pendaftars', 'jalur_id')) {
                $table->dropForeign(['jalur_id']);
                $table->dropColumn('jalur_id');
            }
        });
    }
};