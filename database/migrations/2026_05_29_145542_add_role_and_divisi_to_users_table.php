<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Mengecek apakah kolom 'role' belum ada, jika belum maka tambahkan
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('admin');
            }
            
            // Mengecek apakah kolom 'divisi' belum ada, jika belum maka tambahkan
            if (!Schema::hasColumn('users', 'divisi')) {
                $table->string('divisi')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'divisi')) {
                $table->dropColumn('divisi');
            }
            // Kita biarkan kolom 'role' tidak dihapus saat rollback untuk menghindari error di fitur login lain
        });
    }
};