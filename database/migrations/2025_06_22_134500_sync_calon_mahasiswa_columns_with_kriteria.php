<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Kriteria;

return new class extends Migration
{
    /**
     * Run the migrations.
     */    public function up(): void
    {        // Get existing kriteria codes
        $existingKriteria = Kriteria::pluck('kode')->map(fn($code) => strtolower($code))->toArray();

        // If no kriteria exist yet (first migration), skip sync to avoid dropping columns
        if (empty($existingKriteria)) {
            echo "⚠️  No kriteria found, skipping column sync (will be handled by seeder)\n";
            return;
        }

        // Define all possible criteria columns (expandable as needed)
        $allPossibleColumns = [
            'c1',
            'c2',
            'c3',
            'c4',
            'c5',
            'c6',
            'c7',
            'c8',
            'c9',
            'c10',
            'c11',
            'c12',
            'c13',
            'c14',
            'c15',
            'c16',
            'c17',
            'c18',
            'c19',
            'c20'
        ];

        Schema::table('calon_mahasiswa', function (Blueprint $table) use ($existingKriteria, $allPossibleColumns) {
            foreach ($allPossibleColumns as $column) {
                // If criteria doesn't exist but column exists in database, drop it
                if (!in_array($column, $existingKriteria) && Schema::hasColumn('calon_mahasiswa', $column)) {
                    $table->dropColumn($column);
                    echo "Dropped column: {$column}\n";
                }                // If criteria exists but column doesn't exist, add it
                elseif (in_array($column, $existingKriteria) && !Schema::hasColumn('calon_mahasiswa', $column)) {
                    // Use appropriate data type based on criteria
                    $this->addColumnWithAppropriateType($table, $column);
                    echo "Added column: {$column}\n";
                }
            }
        });

        echo "✅ Calon mahasiswa columns synced with " . count($existingKriteria) . " kriteria\n";
    }

    /**
     * Reverse the migrations.
     */    public function down(): void
    {
        // Restore all columns for rollback (expanded list)
        Schema::table('calon_mahasiswa', function (Blueprint $table) {
            $columns = [
                'c1',
                'c2',
                'c3',
                'c4',
                'c5',
                'c6',
                'c7',
                'c8',
                'c9',
                'c10',
                'c11',
                'c12',
                'c13',
                'c14',
                'c15',
                'c16',
                'c17',
                'c18',
                'c19',
                'c20'
            ];
            foreach ($columns as $column) {
                if (!Schema::hasColumn('calon_mahasiswa', $column)) {
                    $table->decimal($column, 15, 12)->nullable();
                }
            }
        });

        echo "✅ All possible kriteria columns restored for rollback\n";
    }

    /**
     * Add column with appropriate data type based on criteria name patterns
     */
    private function addColumnWithAppropriateType($table, $columnName)
    {
        // Try to get the criteria to determine appropriate type
        try {
            $kriteria = Kriteria::where('kode', strtoupper($columnName))->first();

            if ($kriteria) {
                $kriteriaName = strtolower($kriteria->nama);

                // Determine appropriate data type based on criteria name
                if (str_contains($kriteriaName, 'penghasilan') || str_contains($kriteriaName, 'gaji') || str_contains($kriteriaName, 'income')) {
                    // Income/salary - use bigInteger for large numbers
                    $table->bigInteger($columnName)->nullable();
                } elseif (str_contains($kriteriaName, 'tempat tinggal') || str_contains($kriteriaName, 'lokasi') || str_contains($kriteriaName, 'kondisi')) {
                    // Housing/location - use small integer (1-5 scale)
                    $table->tinyInteger($columnName)->nullable();
                } elseif (str_contains($kriteriaName, 'usia') || str_contains($kriteriaName, 'umur') || str_contains($kriteriaName, 'age')) {
                    // Age - use small integer
                    $table->tinyInteger($columnName)->nullable();
                } elseif (str_contains($kriteriaName, 'jarak') || str_contains($kriteriaName, 'distance')) {
                    // Distance - use decimal for precision
                    $table->decimal($columnName, 8, 2)->nullable();
                } else {
                    // Academic scores, achievements, etc. - use decimal for precision
                    $table->decimal($columnName, 8, 2)->nullable();
                }
            } else {
                // Fallback to decimal if criteria not found
                $table->decimal($columnName, 8, 2)->nullable();
            }
        } catch (\Exception $e) {
            // Fallback to decimal if any error occurs
            $table->decimal($columnName, 8, 2)->nullable();
        }
    }
};
