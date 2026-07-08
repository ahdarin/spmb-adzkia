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
    Schema::create('komponen_biayas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('prodi_id')->constrained('prodis')->onDelete('cascade');
        $table->decimal('spp', 15, 2)->default(0); // Misal: 4000000
        $table->decimal('uang_pangkal', 15, 2)->default(0); // Misal: 5000000
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komponen_biayas');
    }
};
