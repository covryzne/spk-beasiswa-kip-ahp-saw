<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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

            // Generate realistic values based on criteria type and candidate variation
            switch (strtoupper($k->kode)) {
                case 'C1': // Penghasilan Orang Tua (Cost - lower is better)
                    $penghasilans = [2500000, 1800000, 3200000, 1500000, 2800000, 2000000, 3500000, 1200000, 2700000, 1000000];
                    $nilai[$fieldName] = $penghasilans[$candidateIndex] ?? 2000000;
                    break;

                case 'C2': // Tempat Tinggal (Cost - lower is better)
                    $lokasis = [3, 2, 4, 1, 3, 2, 5, 1, 4, 1];
                    $nilai[$fieldName] = $lokasis[$candidateIndex] ?? 3;
                    break;

                case 'C3': // Tes Prestasi (Benefit - higher is better)
                    $prestasis = [85, 92, 78, 88, 82, 90, 76, 86, 81, 94];
                    $nilai[$fieldName] = $prestasis[$candidateIndex] ?? 80;
                    break;

                case 'C4': // Tes Wawancara (Benefit - higher is better)
                    $wawancaras = [80, 88, 75, 85, 79, 87, 72, 83, 78, 92];
                    $nilai[$fieldName] = $wawancaras[$candidateIndex] ?? 80;
                    break;

                case 'C5': // Rata-rata Nilai (Benefit - higher is better)
                    $nilais = [87, 90, 82, 89, 84, 91, 79, 88, 83, 93];
                    $nilai[$fieldName] = $nilais[$candidateIndex] ?? 80;
                    break;

                default:
                    // For dynamic criteria, generate random appropriate values
                    if ($k->jenis === 'Cost') {
                        // Lower values are better for cost criteria
                        $nilai[$fieldName] = rand(1, 5);
                    } else {
                        // Higher values are better for benefit criteria  
                        $nilai[$fieldName] = rand(60, 95);
                    }
                    break;
            }
        }

        return $nilai;
    }

    private function seedMatriksAhp(): void
    {
        $this->command->info('🔢 Seeding Matriks AHP...');

        // Get kriteria IDs
        $kriteriaIds = Kriteria::pluck('id', 'kode')->toArray();

        $matriksAhp = [
            // C1 vs others
            ['kriteria_1_id' => $kriteriaIds['C1'], 'kriteria_2_id' => $kriteriaIds['C1'], 'nilai' => 1.0],
            ['kriteria_1_id' => $kriteriaIds['C1'], 'kriteria_2_id' => $kriteriaIds['C2'], 'nilai' => 2.0],
            ['kriteria_1_id' => $kriteriaIds['C1'], 'kriteria_2_id' => $kriteriaIds['C3'], 'nilai' => 0.5],
            ['kriteria_1_id' => $kriteriaIds['C1'], 'kriteria_2_id' => $kriteriaIds['C4'], 'nilai' => 3.0],
            ['kriteria_1_id' => $kriteriaIds['C1'], 'kriteria_2_id' => $kriteriaIds['C5'], 'nilai' => 0.33],

            // C2 vs others
            ['kriteria_1_id' => $kriteriaIds['C2'], 'kriteria_2_id' => $kriteriaIds['C1'], 'nilai' => 0.5],
            ['kriteria_1_id' => $kriteriaIds['C2'], 'kriteria_2_id' => $kriteriaIds['C2'], 'nilai' => 1.0],
            ['kriteria_1_id' => $kriteriaIds['C2'], 'kriteria_2_id' => $kriteriaIds['C3'], 'nilai' => 0.25],
            ['kriteria_1_id' => $kriteriaIds['C2'], 'kriteria_2_id' => $kriteriaIds['C4'], 'nilai' => 2.0],
            ['kriteria_1_id' => $kriteriaIds['C2'], 'kriteria_2_id' => $kriteriaIds['C5'], 'nilai' => 0.2],

            // C3 vs others
            ['kriteria_1_id' => $kriteriaIds['C3'], 'kriteria_2_id' => $kriteriaIds['C1'], 'nilai' => 2.0],
            ['kriteria_1_id' => $kriteriaIds['C3'], 'kriteria_2_id' => $kriteriaIds['C2'], 'nilai' => 4.0],
            ['kriteria_1_id' => $kriteriaIds['C3'], 'kriteria_2_id' => $kriteriaIds['C3'], 'nilai' => 1.0],
            ['kriteria_1_id' => $kriteriaIds['C3'], 'kriteria_2_id' => $kriteriaIds['C4'], 'nilai' => 1.5],
            ['kriteria_1_id' => $kriteriaIds['C3'], 'kriteria_2_id' => $kriteriaIds['C5'], 'nilai' => 0.67],

            // C4 vs others
            ['kriteria_1_id' => $kriteriaIds['C4'], 'kriteria_2_id' => $kriteriaIds['C1'], 'nilai' => 0.33],
            ['kriteria_1_id' => $kriteriaIds['C4'], 'kriteria_2_id' => $kriteriaIds['C2'], 'nilai' => 0.5],
            ['kriteria_1_id' => $kriteriaIds['C4'], 'kriteria_2_id' => $kriteriaIds['C3'], 'nilai' => 0.67],
            ['kriteria_1_id' => $kriteriaIds['C4'], 'kriteria_2_id' => $kriteriaIds['C4'], 'nilai' => 1.0],
            ['kriteria_1_id' => $kriteriaIds['C4'], 'kriteria_2_id' => $kriteriaIds['C5'], 'nilai' => 0.5],

            // C5 vs others
            ['kriteria_1_id' => $kriteriaIds['C5'], 'kriteria_2_id' => $kriteriaIds['C1'], 'nilai' => 3.0],
            ['kriteria_1_id' => $kriteriaIds['C5'], 'kriteria_2_id' => $kriteriaIds['C2'], 'nilai' => 5.0],
            ['kriteria_1_id' => $kriteriaIds['C5'], 'kriteria_2_id' => $kriteriaIds['C3'], 'nilai' => 1.5],
            ['kriteria_1_id' => $kriteriaIds['C5'], 'kriteria_2_id' => $kriteriaIds['C4'], 'nilai' => 2.0],
            ['kriteria_1_id' => $kriteriaIds['C5'], 'kriteria_2_id' => $kriteriaIds['C5'], 'nilai' => 1.0],
        ];

        foreach ($matriksAhp as $matriks) {
            MatriksAhp::updateOrCreate(
                [
                    'kriteria_1_id' => $matriks['kriteria_1_id'],
                    'kriteria_2_id' => $matriks['kriteria_2_id']
                ],
                $matriks
            );
        }

        $this->command->info('   ✓ 25 Matriks AHP entries created');
    }
}
