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
            $table->integer('ranking')->nullable()->after('rank');
            $table->enum('status', ['diterima', 'ditolak'])->nullable()->after('ranking');
            $table->index(['ranking', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_seleksi', function (Blueprint $table) {
            $table->dropIndex(['ranking', 'status']);
            $table->dropColumn(['ranking', 'status']);
        });
    }
};
