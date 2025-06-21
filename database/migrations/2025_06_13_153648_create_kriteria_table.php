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
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique(); // C1, C2, C3, C4, C5
            $table->string('nama', 100); // Penghasilan Orang Tua, dll
            $table->enum('jenis', ['Cost', 'Benefit']); // Jenis kriteria
            $table->decimal('bobot', 15, 12)->nullable(); // Bobot dari perhitungan AHP
            $table->text('deskripsi')->nullable(); // Deskripsi kriteria
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria');
    }
};
