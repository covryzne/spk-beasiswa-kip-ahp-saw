<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Kriteria;
use App\Models\CalonMahasiswa;
use App\Models\MatriksAhp;

class SPKDataSeeder extends Seeder
{
    /**
     * Run the database seeds for SPK system data.
     */
    public function run(): void
    {
        $this->command->info('🔄 Seeding SPK Data...');

        // Seed Kriteria
        $this->seedKriteria();

        // Sync calon mahasiswa columns with kriteria after kriteria is created
        $this->syncCalonMahasiswaColumns();

        // Seed Calon Mahasiswa
        $this->seedCalonMahasiswa();

        // Seed Matriks AHP
        $this->seedMatriksAhp();

        $this->command->info('✅ SPK Data seeded successfully!');
    }

    private function seedKriteria(): void
    {
        $this->command->info('📋 Seeding Kriteria...');

        $kriteria = [
            [
                'kode' => 'C1',
                'nama' => 'Penghasilan Orang Tua',
                'jenis' => 'cost',
                'bobot' => null, // Will be calculated by AHP
            ],
            [
                'kode' => 'C2',
                'nama' => 'Kondisi Tempat Tinggal',
                'jenis' => 'cost',
                'bobot' => null,
            ],
            [
                'kode' => 'C3',
                'nama' => 'Hasil Tes Prestasi',
                'jenis' => 'benefit',
                'bobot' => null,
            ],
            [
                'kode' => 'C4',
                'nama' => 'Hasil Tes Wawancara',
                'jenis' => 'benefit',
                'bobot' => null,
            ],
            [
                'kode' => 'C5',
                'nama' => 'Rata-Rata Nilai',
                'jenis' => 'benefit',
                'bobot' => null,
            ],
        ];

        foreach ($kriteria as $k) {
            Kriteria::updateOrCreate(['kode' => $k['kode']], $k);
        }

        $this->command->info('   ✓ 5 Kriteria created');
    }

    private function seedCalonMahasiswa(): void
    {
        $this->command->info('👥 Seeding Calon Mahasiswa...');

        // Get available kriteria
        $kriteria = Kriteria::orderBy('kode')->get();

        // Base candidate data
        $baseCandidates = [
            [
                'kode' => 'A01',
                'nama' => 'Ahmad Fauzi',
                'catatan' => 'Kandidat dengan prestasi akademik tinggi',
            ],
            [
                'kode' => 'A02',
                'nama' => 'Siti Nurhaliza',
                'catatan' => 'Calon mahasiswa berprestasi dari keluarga kurang mampu',
            ],
            [
                'kode' => 'A03',
                'nama' => 'Budi Santoso',
                'catatan' => 'Kandidat dengan stabilitas finansial menengah',
            ],
            [
                'kode' => 'A04',
                'nama' => 'Dewi Sartika',
                'catatan' => 'Calon mahasiswa dari daerah terpencil dengan prestasi baik',
            ],
            [
                'kode' => 'A05',
                'nama' => 'Rizki Pratama',
                'catatan' => 'Kandidat dengan potensi akademik yang baik',
            ],
            [
                'kode' => 'A06',
                'nama' => 'Indira Kusuma',
                'catatan' => 'Calon mahasiswa dengan prestasi akademik excellent',
            ],
            [
                'kode' => 'A07',
                'nama' => 'Hendra Wijaya',
                'catatan' => 'Kandidat dengan akses pendidikan yang baik',
            ],
            [
                'kode' => 'A08',
                'nama' => 'Maya Sari',
                'catatan' => 'Calon mahasiswa sangat membutuhkan dengan prestasi tinggi',
            ],
            [
                'kode' => 'A09',
                'nama' => 'Andi Susanto',
                'catatan' => 'Kandidat dengan latar belakang seimbang',
            ],
            [
                'kode' => 'A10',
                'nama' => 'Agus Suryanto',
                'catatan' => 'Calon mahasiswa sangat berprestasi dari keluarga tidak mampu',
            ],
        ];

        // Generate nilai for each candidate based on available kriteria
        foreach ($baseCandidates as $index => $candidate) {
            $nilai = $this->generateNilaiForCandidate($kriteria, $index);
            $candidate = array_merge($candidate, $nilai);

            CalonMahasiswa::updateOrCreate(['kode' => $candidate['kode']], $candidate);
        }

        $this->command->info('   ✓ ' . count($baseCandidates) . ' Calon Mahasiswa created');
    }

    /**
     * Generate nilai for candidate based on available kriteria
     */
    private function generateNilaiForCandidate($kriteria, $candidateIndex): array
    {
        $nilai = [];

        foreach ($kriteria as $k) {
            $fieldName = strtolower($k->kode);

            // Generate realistic values based on criteria type and name pattern
            $nilai[$fieldName] = $this->generateValueForCriteria($k, $candidateIndex);
        }

        return $nilai;
    }

    /**
     * Generate appropriate value for specific criteria based on its type and name
     */
    private function generateValueForCriteria($kriteria, $candidateIndex): mixed
    {
        $kriteriaName = strtolower($kriteria->nama);
        $kriteriaType = strtolower($kriteria->jenis);

        // Detect criteria type based on name patterns
        if (str_contains($kriteriaName, 'penghasilan') || str_contains($kriteriaName, 'gaji') || str_contains($kriteriaName, 'income')) {
            // Income/salary - cost criteria (lower is better) - in Rupiah
            $incomes = [2500000, 1800000, 3200000, 1500000, 2800000, 2000000, 3500000, 1200000, 2700000, 1000000];
            return $incomes[$candidateIndex] ?? rand(1000000, 3500000);
        }

        if (str_contains($kriteriaName, 'tempat tinggal') || str_contains($kriteriaName, 'lokasi') || str_contains($kriteriaName, 'kondisi')) {
            // Housing/location - cost criteria (lower is better) - scale 1-5
            $locations = [3, 2, 4, 1, 3, 2, 5, 1, 4, 1];
            return $locations[$candidateIndex] ?? rand(1, 5);
        }

        if (str_contains($kriteriaName, 'prestasi') || str_contains($kriteriaName, 'achievement')) {
            // Achievement scores - benefit criteria (higher is better) - scale 60-100
            $achievements = [85, 92, 78, 88, 82, 90, 76, 86, 81, 94];
            return $achievements[$candidateIndex] ?? rand(70, 95);
        }

        if (str_contains($kriteriaName, 'wawancara') || str_contains($kriteriaName, 'interview')) {
            // Interview scores - benefit criteria (higher is better) - scale 60-100
            $interviews = [80, 88, 75, 85, 79, 87, 72, 83, 78, 92];
            return $interviews[$candidateIndex] ?? rand(70, 90);
        }

        if (str_contains($kriteriaName, 'nilai') || str_contains($kriteriaName, 'grade') || str_contains($kriteriaName, 'rata')) {
            // Academic grades - benefit criteria (higher is better) - scale 60-100
            $grades = [87, 90, 82, 89, 84, 91, 79, 88, 83, 93];
            return $grades[$candidateIndex] ?? rand(75, 95);
        }

        if (str_contains($kriteriaName, 'usia') || str_contains($kriteriaName, 'umur') || str_contains($kriteriaName, 'age')) {
            // Age - typically cost criteria (younger might be better) - scale 17-25
            $ages = [19, 18, 20, 17, 19, 18, 21, 17, 20, 18];
            return $ages[$candidateIndex] ?? rand(17, 23);
        }

        if (str_contains($kriteriaName, 'jarak') || str_contains($kriteriaName, 'distance')) {
            // Distance - cost criteria (closer is better) - in km
            $distances = [5, 12, 3, 25, 8, 15, 2, 30, 10, 35];
            return $distances[$candidateIndex] ?? rand(1, 40);
        }

        // Generic fallback based on criteria type
        if ($kriteriaType === 'cost') {
            // Cost criteria: lower values are better
            $costValues = [1, 2, 3, 4, 5, 2, 1, 4, 3, 5];
            return $costValues[$candidateIndex] ?? rand(1, 5);
        } else {
            // Benefit criteria: higher values are better
            $benefitValues = [85, 92, 78, 88, 82, 90, 76, 86, 81, 94];
            return $benefitValues[$candidateIndex] ?? rand(70, 95);
        }
    }

    private function seedMatriksAhp(): void
    {
        $this->command->info('🔢 Seeding Matriks AHP...');

        // Get all kriteria ordered by kode
        $kriteria = Kriteria::orderBy('kode')->get();

        if ($kriteria->isEmpty()) {
            $this->command->warn('   ⚠️  No kriteria found, skipping AHP matrix seeding');
            return;
        }

        $totalPairs = 0;

        // Generate pairwise comparison matrix for all criteria combinations
        foreach ($kriteria as $i => $kriteria1) {
            foreach ($kriteria as $j => $kriteria2) {
                // Generate comparison value
                $nilai = $this->generateAhpComparisonValue($kriteria1, $kriteria2, $i, $j);

                MatriksAhp::updateOrCreate(
                    [
                        'kriteria_1_id' => $kriteria1->id,
                        'kriteria_2_id' => $kriteria2->id
                    ],
                    [
                        'kriteria_1_id' => $kriteria1->id,
                        'kriteria_2_id' => $kriteria2->id,
                        'nilai' => $nilai
                    ]
                );

                $totalPairs++;
            }
        }

        $this->command->info("   ✓ {$totalPairs} Matriks AHP entries created for " . $kriteria->count() . " criteria");
    }

    /**
     * Generate AHP comparison value between two criteria
     */
    private function generateAhpComparisonValue($kriteria1, $kriteria2, $index1, $index2): float
    {
        // Diagonal elements (same criteria comparison)
        if ($kriteria1->id === $kriteria2->id) {
            return 1.0;
        }

        // Create consistent comparison values based on criteria names and types
        $priority1 = $this->getCriteriaPriority($kriteria1);
        $priority2 = $this->getCriteriaPriority($kriteria2);

        // Calculate comparison based on priority difference
        $priorityDiff = $priority1 - $priority2;

        if ($priorityDiff > 0) {
            // Kriteria1 is more important than Kriteria2
            $scale = min(abs($priorityDiff) + 1, 9); // AHP scale 1-9
            return (float) $scale;
        } elseif ($priorityDiff < 0) {
            // Kriteria2 is more important than Kriteria1
            $scale = min(abs($priorityDiff) + 1, 9);
            return round(1.0 / $scale, 2);
        } else {
            // Equal importance
            return 1.0;
        }
    }

    /**
     * Get priority weight for criteria based on its characteristics
     */
    private function getCriteriaPriority($kriteria): int
    {
        $kriteriaName = strtolower($kriteria->nama);

        // Higher number = higher priority in scholarship selection

        // Financial need criteria (highest priority)
        if (str_contains($kriteriaName, 'penghasilan') || str_contains($kriteriaName, 'gaji') || str_contains($kriteriaName, 'income')) {
            return 5; // Very high priority
        }

        if (str_contains($kriteriaName, 'tempat tinggal') || str_contains($kriteriaName, 'kondisi') || str_contains($kriteriaName, 'ekonomi')) {
            return 4; // High priority
        }

        // Academic criteria (medium-high priority)
        if (str_contains($kriteriaName, 'nilai') || str_contains($kriteriaName, 'grade') || str_contains($kriteriaName, 'rata')) {
            return 4; // High priority
        }

        if (str_contains($kriteriaName, 'prestasi') || str_contains($kriteriaName, 'achievement')) {
            return 3; // Medium-high priority
        }

        // Soft skills criteria (medium priority)
        if (str_contains($kriteriaName, 'wawancara') || str_contains($kriteriaName, 'interview')) {
            return 2; // Medium priority
        }

        // Other criteria (lower priority)
        if (str_contains($kriteriaName, 'usia') || str_contains($kriteriaName, 'umur') || str_contains($kriteriaName, 'age')) {
            return 1; // Lower priority
        }

        if (str_contains($kriteriaName, 'jarak') || str_contains($kriteriaName, 'distance')) {
            return 1; // Lower priority
        }

        // Default priority based on criteria type
        return $kriteria->jenis === 'cost' ? 3 : 2; // Cost criteria generally more important for scholarships
    }

    /**
     * Sync calon mahasiswa columns with existing kriteria
     */
    private function syncCalonMahasiswaColumns(): void
    {
        $this->command->info('🔗 Syncing calon mahasiswa columns with kriteria...');

        try {
            // Get existing kriteria codes
            $existingKriteria = Kriteria::pluck('kode')->map(fn($code) => strtolower($code))->toArray();

            if (empty($existingKriteria)) {
                $this->command->warn('   ⚠️  No kriteria found, skipping column sync');
                return;
            }

            // Define max possible columns
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
                        $this->command->warn("   🗑️  Dropped unused column: {$column}");
                    }
                    // If criteria exists but column doesn't exist, add it
                    elseif (in_array($column, $existingKriteria) && !Schema::hasColumn('calon_mahasiswa', $column)) {
                        $this->addColumnWithAppropriateType($table, $column);
                        $this->command->info("   ➕ Added column: {$column}");
                    }
                }
            });

            $this->command->info("   ✓ Synced with " . count($existingKriteria) . " kriteria columns");
        } catch (\Exception $e) {
            $this->command->error('   ❌ Failed to sync columns: ' . $e->getMessage());
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
