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
            // Add additional normalized columns for dynamic criteria (c6-c10)
            if (!Schema::hasColumn('hasil_seleksi', 'c6_normalized')) {
                $table->decimal('c6_normalized', 15, 12)->nullable()->after('c5_normalized');
            }
            if (!Schema::hasColumn('hasil_seleksi', 'c7_normalized')) {
                $table->decimal('c7_normalized', 15, 12)->nullable()->after('c6_normalized');
            }
            if (!Schema::hasColumn('hasil_seleksi', 'c8_normalized')) {
                $table->decimal('c8_normalized', 15, 12)->nullable()->after('c7_normalized');
            }
            if (!Schema::hasColumn('hasil_seleksi', 'c9_normalized')) {
                $table->decimal('c9_normalized', 15, 12)->nullable()->after('c8_normalized');
            }
            if (!Schema::hasColumn('hasil_seleksi', 'c10_normalized')) {
                $table->decimal('c10_normalized', 15, 12)->nullable()->after('c9_normalized');
            }

            // Add JSON column for completely dynamic normalized values (already exists)
            // if (!Schema::hasColumn('hasil_seleksi', 'normalized_values')) {
            //     $table->json('normalized_values')->nullable()->after('c10_normalized');
            // }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_seleksi', function (Blueprint $table) {
            // Only drop columns that actually exist and were added by this migration
            $columnsToCheck = [
                'normalized_values',
                'criteria_used',
                'calculated_at',
                'c6_normalized',
                'c7_normalized',
                'c8_normalized',
                'c9_normalized',
                'c10_normalized'
            ];

            $columnsToDrop = [];
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('hasil_seleksi', $column)) {
                    $columnsToDrop[] = $column;
                }
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
