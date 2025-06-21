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
        Schema::create('perhitungan_ahp', function (Blueprint $table) {
            $table->id();
            $table->decimal('lambda_max', 10, 7); // Lambda maksimum
            $table->decimal('ci', 10, 7); // Consistency Index
            $table->decimal('ri', 10, 7); // Random Index
            $table->decimal('cr', 10, 7); // Consistency Ratio
            $table->boolean('is_consistent')->default(false); // Apakah konsisten (CR < 0.1)
            $table->json('eigen_vector')->nullable(); // Eigen vector untuk bobot
            $table->json('matriks_normalized')->nullable(); // Matriks ternormalisasi
            $table->date('tanggal_perhitungan'); // Tanggal perhitungan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perhitungan_ahp');
    }
};
