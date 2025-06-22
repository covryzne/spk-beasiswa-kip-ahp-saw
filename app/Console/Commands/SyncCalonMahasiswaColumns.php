<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Kriteria;

class SyncCalonMahasiswaColumns extends Command
{
    protected $signature = 'app:sync-calon-mahasiswa-columns';
    protected $description = 'Sync calon_mahasiswa table columns with existing kriteria';

    public function handle()
    {
        $this->info('🔄 Syncing calon_mahasiswa columns with kriteria...');

        // Get existing kriteria
        $existingKriteria = Kriteria::orderBy('kode')->get();
        $kriteriaColumns = $existingKriteria->pluck('kode')->map(fn($code) => strtolower($code))->toArray();

        $this->info('📋 Existing kriteria: ' . implode(', ', $kriteriaColumns));

        // Get existing columns in calon_mahasiswa table
        $tableColumns = Schema::getColumnListing('calon_mahasiswa');
        $criteriaColumnsInTable = array_filter($tableColumns, fn($col) => preg_match('/^c\d+$/', $col));

        $this->info('📋 Existing columns in table: ' . implode(', ', $criteriaColumnsInTable));

        // Columns to add
        $columnsToAdd = array_diff($kriteriaColumns, $criteriaColumnsInTable);

        // Columns to remove
        $columnsToRemove = array_diff($criteriaColumnsInTable, $kriteriaColumns);

        // Add missing columns
        if (!empty($columnsToAdd)) {
            $this->info('➕ Adding columns: ' . implode(', ', $columnsToAdd));
            Schema::table('calon_mahasiswa', function (Blueprint $table) use ($columnsToAdd) {
                foreach ($columnsToAdd as $column) {
                    $table->decimal($column, 15, 12)->nullable()->after('nama');
                }
            });
        }

        // Remove unused columns
        if (!empty($columnsToRemove)) {
            $this->warn('🗑️  Removing unused columns: ' . implode(', ', $columnsToRemove));
            $confirm = $this->confirm('This will delete data in these columns. Continue?', false);

            if ($confirm) {
                Schema::table('calon_mahasiswa', function (Blueprint $table) use ($columnsToRemove) {
                    $table->dropColumn($columnsToRemove);
                });
                $this->info('✅ Columns removed successfully.');
            } else {
                $this->info('⚠️  Column removal skipped.');
            }
        }

        if (empty($columnsToAdd) && empty($columnsToRemove)) {
            $this->info('✅ Table already in sync with kriteria.');
        } else {
            $this->info('✅ Sync completed successfully!');
        }

        return 0;
    }
}
