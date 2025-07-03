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
        Schema::table('perhitungan_ahp', function (Blueprint $table) {
            $table->json('weighted_sum')->nullable()->after('matriks_normalized')
                ->comment('Hasil perkalian matrix perbandingan dengan priority weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perhitungan_ahp', function (Blueprint $table) {
            $table->dropColumn('weighted_sum');
        });
    }
};
