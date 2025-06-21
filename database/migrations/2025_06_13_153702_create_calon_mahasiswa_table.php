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
        Schema::create('calon_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique(); // A01, A02, dst
            $table->string('nama', 100);
            $table->decimal('c1', 15, 2); // Penghasilan Orang Tua (rupiah)
            $table->integer('c2'); // Tempat Tinggal (1-5)
            $table->decimal('c3', 5, 2); // Hasil Tes Prestasi (0-100)
            $table->decimal('c4', 5, 2); // Hasil Tes Wawancara (0-100)
            $table->decimal('c5', 5, 2); // Rata-Rata Nilai (0-100)
            $table->text('catatan')->nullable(); // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calon_mahasiswa');
    }
};
