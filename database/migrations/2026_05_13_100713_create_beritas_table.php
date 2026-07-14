<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beritas', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori');
            $table->string('slug');
            $table->text('ringkasan')->nullable();
            $table->longText('konten')->nullable();
            $table->string('status')->default('Draft');
            $table->string('thumbnail')->nullable();
            $table->timestamp('tanggal_publish')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beritas');
    }
};