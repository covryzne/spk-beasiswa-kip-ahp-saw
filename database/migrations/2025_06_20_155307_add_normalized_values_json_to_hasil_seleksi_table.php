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
        Schema::table('hasil_seleksi', function (Blueprint $table) {
            // Add JSON column for completely dynamic normalized values
            $table->json('normalized_values')->nullable()->after('c10_normalized');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_seleksi', function (Blueprint $table) {
            $table->dropColumn('normalized_values');
        });
    }
};
