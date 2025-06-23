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
        Schema::table('calon_mahasiswa', function (Blueprint $table) {
            $table->unsignedBigInteger('data_mahasiswa_id')->nullable()->after('id');
            $table->foreign('data_mahasiswa_id')->references('id')->on('data_mahasiswa')->onDelete('cascade');
            $table->index('data_mahasiswa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calon_mahasiswa', function (Blueprint $table) {
            $table->dropForeign(['data_mahasiswa_id']);
            $table->dropColumn('data_mahasiswa_id');
        });
    }
};
