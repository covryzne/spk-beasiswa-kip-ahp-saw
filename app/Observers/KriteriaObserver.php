<?php

namespace App\Observers;

use App\Models\Kriteria;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class KriteriaObserver
{
    /**
     * Handle the Kriteria "created" event.
     */
    public function created(Kriteria $kriteria): void
    {
        $this->syncCalonMahasiswaColumns();
    }

    /**
     * Handle the Kriteria "updated" event.
     */
    public function updated(Kriteria $kriteria): void
    {
        // If kode was changed, sync columns
        if ($kriteria->wasChanged('kode')) {
            $this->syncCalonMahasiswaColumns();
        }
    }

    /**
     * Handle the Kriteria "deleted" event.
     */
    public function deleted(Kriteria $kriteria): void
    {
        $this->syncCalonMahasiswaColumns();
    }

    /**
     * Handle the Kriteria "restored" event.
     */
    public function restored(Kriteria $kriteria): void
    {
        $this->syncCalonMahasiswaColumns();
    }

    /**
     * Handle the Kriteria "force deleted" event.
     */
    public function forceDeleted(Kriteria $kriteria): void
    {
        $this->syncCalonMahasiswaColumns();
    }

    /**
     * Sync calon_mahasiswa table columns with kriteria
     */
    private function syncCalonMahasiswaColumns(): void
    {
        try {
            // Get current kriteria codes
            $existingKriteria = Kriteria::pluck('kode')->map(fn($code) => strtolower($code))->toArray();

            // Define max possible columns (adjust based on your needs)
            $allPossibleColumns = ['c1', 'c2', 'c3', 'c4', 'c5', 'c6', 'c7', 'c8', 'c9', 'c10', 'c11', 'c12', 'c13', 'c14', 'c15'];

            Schema::table('calon_mahasiswa', function (Blueprint $table) use ($existingKriteria, $allPossibleColumns) {
                foreach ($allPossibleColumns as $column) {
                    // If criteria doesn't exist but column exists in database, drop it
                    if (!in_array($column, $existingKriteria) && Schema::hasColumn('calon_mahasiswa', $column)) {
                        $table->dropColumn($column);
                        Log::info("Dropped column '{$column}' from calon_mahasiswa table");
                    }                    // If criteria exists but column doesn't exist, add it
                    elseif (in_array($column, $existingKriteria) && !Schema::hasColumn('calon_mahasiswa', $column)) {
                        $this->addColumnWithAppropriateType($table, $column);
                        Log::info("Added column '{$column}' to calon_mahasiswa table");
                    }
                }
            });

            // Clear model cache to reflect new columns
            if (method_exists(\App\Models\CalonMahasiswa::class, 'flushEventListeners')) {
                \App\Models\CalonMahasiswa::flushEventListeners();
            }

            Log::info('Calon mahasiswa table columns synced with kriteria');
        } catch (\Exception $e) {
            Log::error('Failed to sync calon_mahasiswa columns: ' . $e->getMessage());
        }
    }

    /**
     * Add column with appropriate data type based on criteria name patterns
     */
    private function addColumnWithAppropriateType($table, $columnName): void
    {
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
}
