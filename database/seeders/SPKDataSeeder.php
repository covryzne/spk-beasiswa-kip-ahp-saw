<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;
use App\Models\DataMahasiswa;
use App\Models\CalonMahasiswa;
use App\Models\MatriksAhp;
use Illuminate\Support\Facades\Schema;

class SPKDataSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $this->command->info('🔄 Starting SPK Data Seeding...');

        // 1. Seed Kriteria
        $this->seedKriteria();

        // 2. Sync CalonMahasiswa columns with Kriteria
        $this->syncCalonMahasiswaColumns();

        // 3. Seed CalonMahasiswa from existing DataMahasiswa
        $this->seedCalonMahasiswaFromDataMahasiswa();

        // 4. Generate Default AHP Matrix
        $this->generateDefaultAhpMatrix();

        $this->command->info('✅ SPK Data seeding completed successfully!');
    }

    /**
     * Seed Kriteria data
     */
    private function seedKriteria(): void
    {
        $this->command->info('📝 Seeding Kriteria...');
        $kriteriaData = [
            [
                'kode' => 'C1',
                'nama' => 'Penghasilan Orang Tua',
                'bobot' => 0.2222, // Bobot 7
                'jenis' => 'Cost',
                'deskripsi' => 'Penghasilan bulanan orang tua/wali (Rp)',
            ],
            [
                'kode' => 'C2',
                'nama' => 'Kondisi Tempat Tinggal',
                'bobot' => 0.1587, // Bobot 5
                'jenis' => 'Cost',
                'deskripsi' => 'Kondisi fisik rumah tempat tinggal',
            ],
            [
                'kode' => 'C3',
                'nama' => 'Prestasi',
                'bobot' => 0.2222, // Bobot 7
                'jenis' => 'Benefit',
                'deskripsi' => 'Prestasi akademik atau non-akademik',
            ],
            [
                'kode' => 'C4',
                'nama' => 'Pekerjaan Orang Tua',
                'bobot' => 0.0317, // Bobot 1
                'jenis' => 'Cost',
                'deskripsi' => 'Status pekerjaan orang tua/wali',
            ],
            [
                'kode' => 'C5',
                'nama' => 'Komitmen Kuliah',
                'bobot' => 0.2857, // Bobot 9
                'jenis' => 'Benefit',
                'deskripsi' => 'Komitmen dan motivasi untuk menyelesaikan kuliah',
            ],
            [
                'kode' => 'C6',
                'nama' => 'Aset Keluarga',
                'bobot' => 0.1587, // Bobot 5
                'jenis' => 'Cost',
                'deskripsi' => 'Kepemilikan aset dan properti keluarga',
            ],
            [
                'kode' => 'C7',
                'nama' => 'Kartu Bantuan Sosial',
                'bobot' => 0.2222, // Bobot 7
                'jenis' => 'Benefit',
                'deskripsi' => 'Kepemilikan kartu bantuan sosial (KIP, PKH, KKS)',
            ],
        ];

        foreach ($kriteriaData as $data) {
            Kriteria::firstOrCreate(
                ['kode' => $data['kode']],
                $data
            );
        }

        $this->command->info('   ✅ Kriteria seeded successfully!');
    }

    /**
     * Sync CalonMahasiswa table columns with Kriteria
     */
    private function syncCalonMahasiswaColumns(): void
    {
        $this->command->info('🔄 Syncing CalonMahasiswa columns...');

        try {
            // Get all kriteria codes
            $kriteriaCodes = Kriteria::pluck('kode')->map(fn($code) => strtolower($code))->toArray();

            // Get existing columns
            $existingColumns = Schema::getColumnListing('calon_mahasiswa');

            // Define base columns that should always exist
            $baseColumns = ['id', 'kode', 'nama', 'catatan', 'data_mahasiswa_id', 'created_at', 'updated_at'];

            // Add missing kriteria columns
            foreach ($kriteriaCodes as $column) {
                if (!in_array($column, $existingColumns)) {
                    Schema::table('calon_mahasiswa', function ($table) use ($column) {
                        $table->decimal($column, 10, 2)->nullable()->after('catatan');
                    });
                    $this->command->info("   ➕ Added column: {$column}");
                }
            }

            // Remove extra columns that are not in kriteria or base columns
            $allowedColumns = array_merge($baseColumns, $kriteriaCodes);
            foreach ($existingColumns as $column) {
                if (!in_array($column, $allowedColumns)) {
                    // Only drop if it's a criteria-like column (c1, c2, etc.)
                    if (preg_match('/^c\d+$/', $column)) {
                        Schema::table('calon_mahasiswa', function ($table) use ($column) {
                            $table->dropColumn($column);
                        });
                        $this->command->info("   ➖ Removed column: {$column}");
                    }
                }
            }

            $this->command->info('   ✅ CalonMahasiswa columns synced successfully!');
        } catch (\Exception $e) {
            $this->command->warn("   ⚠️ Error syncing columns: " . $e->getMessage());
        }
    }

    /**
     * Seed CalonMahasiswa from existing DataMahasiswa
     */
    private function seedCalonMahasiswaFromDataMahasiswa(): void
    {
        $this->command->info('📝 Seeding CalonMahasiswa from DataMahasiswa...');

        $dataMahasiswaList = DataMahasiswa::all();

        if ($dataMahasiswaList->isEmpty()) {
            $this->command->warn('   ⚠️ No DataMahasiswa found! Make sure to run DataMahasiswaSeeder first.');
            return;
        }

        foreach ($dataMahasiswaList as $dataMahasiswa) {
            // Check if CalonMahasiswa already exists for this DataMahasiswa
            $existingCalon = CalonMahasiswa::where('data_mahasiswa_id', $dataMahasiswa->id)->first();

            if (!$existingCalon) {
                // Create new CalonMahasiswa with mapped values
                $calonData = $this->mapDataMahasiswaToCalonMahasiswa($dataMahasiswa);
                CalonMahasiswa::create($calonData);

                $this->command->info("   ➕ Created CalonMahasiswa: {$dataMahasiswa->nama}");
            } else {
                // Update existing CalonMahasiswa with latest mapped values
                $calonData = $this->mapDataMahasiswaToCalonMahasiswa($dataMahasiswa);
                unset($calonData['kode']); // Don't update kode
                $existingCalon->update($calonData);

                $this->command->info("   🔄 Updated CalonMahasiswa: {$dataMahasiswa->nama}");
            }
        }

        $this->command->info('   ✅ CalonMahasiswa seeded successfully!');
    }

    /**
     * Map DataMahasiswa to CalonMahasiswa format
     */
    private function mapDataMahasiswaToCalonMahasiswa(DataMahasiswa $dataMahasiswa): array
    {
        $data = [
            'data_mahasiswa_id' => $dataMahasiswa->id,
            'nama' => $dataMahasiswa->nama,
            'kode' => 'CM-' . str_pad($dataMahasiswa->id, 3, '0', STR_PAD_LEFT),
            'catatan' => 'Auto-generated from DataMahasiswa',
        ];

        // Get kriteria mapping
        $mapping = DataMahasiswa::getCriteriaMapping();
        $kriteria = Kriteria::orderBy('kode')->get();

        foreach ($kriteria as $k) {
            $column = strtolower($k->kode);

            if (isset($mapping[$k->kode])) {
                $sourceColumn = $mapping[$k->kode];
                $rawValue = $dataMahasiswa->{$sourceColumn};

                // Convert to numeric value for SPK calculation
                $numericValue = $this->convertToNumericValue($sourceColumn, $rawValue);
                $data[$column] = $numericValue;
            } else {
                // Default value if no mapping found
                $data[$column] = rand(1, 5);
            }
        }

        return $data;
    }
    /**
     * Convert various data types to numeric values for SPK
     * Updated scoring system: Cost (lower = better), Benefit (higher = better)
     */
    private function convertToNumericValue(string $column, $value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        // Convert based on column type with proper Cost/Benefit scoring
        switch ($column) {
            case 'penghasilan_orang_tua':
                return (float) $value; // Already numeric

            case 'kondisi_rumah':
                // COST: Kondisi buruk = nilai kecil = prioritas tinggi
                $kondisiMap = [
                    'Sangat Kurang' => 1,  // Prioritas tertinggi
                    'Kurang' => 2,
                    'Cukup' => 3,
                    'Baik' => 4,
                    'Sangat Baik' => 5,    // Prioritas terendah
                ];
                return $kondisiMap[$value] ?? 3;

            case 'prestasi':
                // BENEFIT: Prestasi tinggi = nilai besar = prioritas tinggi (0-100 scale)
                if (is_numeric($value)) {
                    return (float) $value; // Direct numeric input
                }

                // Text-based prestasi conversion to 0-100 scale
                if (empty($value) || strtolower($value) === 'tidak ada prestasi khusus') return 0;
                if (str_contains(strtolower($value), 'juara 1') || str_contains(strtolower($value), 'nasional')) return 100;
                if (str_contains(strtolower($value), 'juara 2') || str_contains(strtolower($value), 'provinsi')) return 85;
                if (str_contains(strtolower($value), 'juara 3') || str_contains(strtolower($value), 'kabupaten')) return 75;
                if (str_contains(strtolower($value), 'juara') || str_contains(strtolower($value), 'lomba')) return 60;
                if (str_contains(strtolower($value), 'sertifikat') || str_contains(strtolower($value), 'pelatihan')) return 40;
                return 20; // Ada prestasi tapi tidak spesifik

            case 'status_bekerja':
            case 'pekerjaan_ortu':
                // COST: Tidak bekerja/pengangguran = butuh beasiswa = nilai kecil = prioritas tinggi
                $pekerjaanMap = [
                    'Tidak Bekerja' => 1,
                    'Pengangguran' => 1,
                    'Buruh Harian' => 2,
                    'Petani' => 2,
                    'Pedagang Kecil' => 3,
                    'Karyawan' => 4,
                    'PNS' => 5,
                    'Wiraswasta' => 4,
                ];
                return $pekerjaanMap[$value] ?? 2;

            case 'komitmen_kuliah':
                // BENEFIT: Komitmen tinggi = nilai besar = prioritas tinggi
                $komitmenMap = [
                    'Sangat Rendah' => 1,
                    'Rendah' => 2,
                    'Cukup' => 3,
                    'Tinggi' => 4,
                    'Sangat Tinggi' => 5,
                ];
                return $komitmenMap[$value] ?? 3;

            case 'aset_keluarga':
                // COST: Aset sedikit = butuh beasiswa = nilai kecil = prioritas tinggi
                $asetMap = [
                    'Tidak Ada' => 1,
                    'Sangat Sedikit' => 1,
                    'Sedikit' => 2,
                    'Cukup' => 3,
                    'Banyak' => 4,
                    'Sangat Banyak' => 5,
                ];
                return $asetMap[$value] ?? 2;

            case 'kartu_bantuan':
                // BENEFIT: Punya kartu bantuan = butuh beasiswa = nilai besar = prioritas tinggi
                $kartuMap = [
                    'Tidak Ada' => 1,
                    'KIP' => 4,
                    'PKH' => 4,
                    'KKS' => 4,
                    'KIP + PKH' => 5,
                    'KIP + KKS' => 5,
                    'PKH + KKS' => 5,
                    'Lengkap (KIP + PKH + KKS)' => 5,
                ];
                return $kartuMap[$value] ?? 1;

            case 'support_orang_tua':
                // BENEFIT: Dukungan tinggi = nilai besar = prioritas tinggi
                $supportMap = [
                    'Kurang Mendukung' => 1,
                    'Cukup Mendukung' => 2,
                    'Mendukung' => 3,
                    'Sangat Mendukung' => 4,
                ];
                return $supportMap[$value] ?? 2;

            default:
                return 2.0; // Default neutral value
        }
    }

    /**
     * Generate default AHP matrix for kriteria
     */
    private function generateDefaultAhpMatrix(): void
    {
        $this->command->info('🔢 Generating default AHP matrix...');

        $kriteria = Kriteria::orderBy('kode')->get();

        if ($kriteria->count() < 2) {
            $this->command->warn('   ⚠️ Need at least 2 criteria for AHP matrix');
            return;
        }

        foreach ($kriteria as $i => $kriteriaI) {
            foreach ($kriteria as $j => $kriteriaJ) {
                // Check if matrix entry already exists
                $existing = MatriksAhp::where('kriteria_1_id', $kriteriaI->id)
                    ->where('kriteria_2_id', $kriteriaJ->id)
                    ->first();

                if (!$existing) {
                    $nilai = $this->generateDefaultComparisonValue($kriteriaI, $kriteriaJ);

                    MatriksAhp::create([
                        'kriteria_1_id' => $kriteriaI->id,
                        'kriteria_2_id' => $kriteriaJ->id,
                        'nilai' => $nilai
                    ]);
                }
            }
        }

        $this->command->info('   ✅ Default AHP matrix generated successfully!');
    }

    /**
     * Generate default comparison value between two criteria
     */
    private function generateDefaultComparisonValue($kriteria1, $kriteria2): float
    {
        // Same criteria
        if ($kriteria1->id === $kriteria2->id) {
            return 1.0;
        }

        // Get priority weights based on criteria names for scholarship context
        $priority1 = $this->getCriteriaPriority($kriteria1);
        $priority2 = $this->getCriteriaPriority($kriteria2);

        $priorityDiff = $priority1 - $priority2;

        if ($priorityDiff > 0) {
            $scale = min(abs($priorityDiff) + 1, 9);
            return (float) $scale;
        } elseif ($priorityDiff < 0) {
            $scale = min(abs($priorityDiff) + 1, 9);
            return round(1.0 / $scale, 2);
        } else {
            return 1.0;
        }
    }

    /**
     * Get priority weight for criteria based on scholarship context and new bobot system
     */
    private function getCriteriaPriority($kriteria): int
    {
        $kriteriaCode = strtoupper($kriteria->kode);

        // Priority based on new bobot system:
        // C5 Komitmen Kuliah (Bobot 9) - Priority 9
        // C1 Penghasilan Orang Tua (Bobot 7) - Priority 7  
        // C3 Prestasi (Bobot 7) - Priority 7
        // C7 Kartu Bantuan Sosial (Bobot 7) - Priority 7
        // C2 Kondisi Tempat Tinggal (Bobot 5) - Priority 5
        // C6 Aset Keluarga (Bobot 5) - Priority 5
        // C4 Pekerjaan Orang Tua (Bobot 1) - Priority 1

        switch ($kriteriaCode) {
            case 'C1': // Penghasilan Orang Tua
                return 7;
            case 'C2': // Kondisi Tempat Tinggal
                return 5;
            case 'C3': // Prestasi
                return 7;
            case 'C4': // Pekerjaan Orang Tua
                return 1;
            case 'C5': // Komitmen Kuliah
                return 9;
            case 'C6': // Aset Keluarga
                return 5;
            case 'C7': // Kartu Bantuan Sosial
                return 7;
            default:
                return 3; // Default priority
        }
    }
}
