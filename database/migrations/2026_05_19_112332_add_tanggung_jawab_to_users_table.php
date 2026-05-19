<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom divisi/tanggung jawab
            $table->string('tanggung_jawab')->default('Belum Ditentukan')->after('email');
            $table->string('role')->default('admin')->after('tanggung_jawab'); // Memastikan ada pembeda user/admin
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tanggung_jawab', 'role']);
        });
    }
};