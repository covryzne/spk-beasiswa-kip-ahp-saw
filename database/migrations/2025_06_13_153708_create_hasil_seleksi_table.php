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
        Schema::create('hasil_seleksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calon_mahasiswa_id')->constrained('calon_mahasiswa')->onDelete('cascade');
            $table->decimal('skor', 15, 12); // Skor hasil perhitungan SAW
            $table->integer('rank'); // Ranking calon mahasiswa
            $table->decimal('c1_normalized', 15, 12)->nullable(); // Nilai normalisasi C1
            $table->decimal('c2_normalized', 15, 12)->nullable(); // Nilai normalisasi C2
            $table->decimal('c3_normalized', 15, 12)->nullable(); // Nilai normalisasi C3
            $table->decimal('c4_normalized', 15, 12)->nullable(); // Nilai normalisasi C4
            $table->decimal('c5_normalized', 15, 12)->nullable(); // Nilai normalisasi C5
            $table->date('tanggal_seleksi'); // Tanggal perhitungan seleksi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_seleksi');
    }
};
