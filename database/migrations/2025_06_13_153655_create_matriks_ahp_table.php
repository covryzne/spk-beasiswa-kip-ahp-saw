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
        Schema::create('matriks_ahp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kriteria_1_id')->constrained('kriteria')->onDelete('cascade');
            $table->foreignId('kriteria_2_id')->constrained('kriteria')->onDelete('cascade');
            $table->decimal('nilai', 10, 7); // Nilai perbandingan (1, 3, 5, 7, 9, atau 1/3, 1/5, dll)
            $table->timestamps();

            // Unique constraint untuk kombinasi kriteria
            $table->unique(['kriteria_1_id', 'kriteria_2_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matriks_ahp');
    }
};
