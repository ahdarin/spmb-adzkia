<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_pendaftars', function (Blueprint $table) {
            // Ini kolom BARU khusus untuk kelulusan
            if (!Schema::hasColumn('data_pendaftars', 'status_kelulusan')) {
                $table->string('status_kelulusan')->nullable()->after('status_pendaftaran');
            }
        });
    }

    public function down(): void
    {
        Schema::table('data_pendaftars', function (Blueprint $table) {
            if (Schema::hasColumn('data_pendaftars', 'status_kelulusan')) {
                $table->dropColumn('status_kelulusan');
            }
        });
    }
};