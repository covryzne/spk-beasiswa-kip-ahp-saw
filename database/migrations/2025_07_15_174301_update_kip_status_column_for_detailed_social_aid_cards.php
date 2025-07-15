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
        Schema::table('data_mahasiswa', function (Blueprint $table) {
            // Change kip_status from enum to varchar to support detailed social aid card combinations
            $table->string('kip_status', 100)->change();

            // Change kendaraan from enum to varchar to support "Sepeda Motor" and other variations
            $table->string('kendaraan', 50)->change();

            // Change kondisi_ekonomi from enum to varchar to support "Sangat Sedikit", "Banyak", etc.
            $table->string('kondisi_ekonomi', 50)->change();

            // Change sumber_air from enum to varchar to support "PAM" and other variations
            $table->string('sumber_air', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_mahasiswa', function (Blueprint $table) {
            // Revert back to original enum values
            $table->enum('kip_status', ['Ya', 'Tidak'])->change();
            $table->enum('kendaraan', ['Motor', 'Mobil', 'Sepeda', 'Tidak Ada'])->change();
            $table->enum('kondisi_ekonomi', ['Surplus', 'Cukup', 'Defisit', 'Berhutang'])->change();
            $table->enum('sumber_air', ['PDAM', 'Sumur Bor', 'Sumur Gali', 'Air Hujan', 'Sungai/Mata Air'])->change();
        });
    }
};
