<?php

namespace App\Services;

use App\Models\CalonMahasiswa;
use App\Models\Kriteria;
use App\Models\HasilSeleksi;
use Illuminate\Support\Facades\Schema;

class SawService
{
    /**
     * Calculate SAW ranking
     */
    public function calculateSaw(): array
    {
        $kriteria = Kriteria::orderBy('kode')->get();
        $calonMahasiswa = CalonMahasiswa::orderBy('kode')->get();

        if ($kriteria->isEmpty()) {
            throw new \Exception('Tidak ada kriteria yang tersedia');
        }

        if ($calonMahasiswa->isEmpty()) {
            throw new \Exception('Tidak ada calon mahasiswa yang tersedia');
        }

        // Check if AHP weights are available
        $hasWeights = $kriteria->every(function ($k) {
            return $k->bobot !== null;
        });

        if (!$hasWeights) {
            throw new \Exception('Bobot kriteria belum dihitung. Lakukan perhitungan AHP terlebih dahulu.');
        }

        // Get decision matrix
        $decisionMatrix = $this->getDecisionMatrix($calonMahasiswa);

        // Normalize decision matrix
        $normalizedMatrix = $this->normalizeMatrix($decisionMatrix, $kriteria);

        // Calculate scores
        $scores = $this->calculateScores($normalizedMatrix, $kriteria);

        // Create ranking
        $ranking = $this->createRanking($calonMahasiswa, $scores, $normalizedMatrix);

        // Save results
        $this->saveResults($ranking);

        return [
            'decision_matrix' => $decisionMatrix,
            'normalized_matrix' => $normalizedMatrix,
            'scores' => $scores,
            'ranking' => $ranking,
        ];
    }
    /**
     * Get decision matrix from calon mahasiswa data
     */
    private function getDecisionMatrix($calonMahasiswa): array
    {
        $kriteria = Kriteria::orderBy('kode')->get();
        $matrix = [];

        foreach ($calonMahasiswa as $index => $calon) {
            $row = [
                'id' => $calon->id,
                'kode' => $calon->kode,
                'nama' => $calon->nama,
            ];

            // Add dynamic criteria values
            foreach ($kriteria as $k) {
                $fieldName = strtolower($k->kode); // c1, c2, c3, etc.
                $row[$fieldName] = (float) ($calon->{$fieldName} ?? 0);
            }

            $matrix[$index] = $row;
        }

        return $matrix;
    }
    /**
     * Normalize decision matrix using min-max normalization
     */
    private function normalizeMatrix(array $decisionMatrix, $kriteria): array
    {
        $normalizedMatrix = $decisionMatrix;
        foreach ($kriteria as $index => $kriteriaItem) {
            $fieldName = strtolower($kriteriaItem->kode); // c1, c2, c3, etc.
            $values = array_column($decisionMatrix, $fieldName);

            if (empty($values)) {
                continue; // Skip if no values for this criteria
            }
            if ($kriteriaItem->jenis === 'Cost') {
                // For cost criteria: min/value
                $minValue = min($values);
                foreach ($normalizedMatrix as &$row) {
                    $currentValue = (float) $row[$fieldName];
                    $minValueFloat = (float) $minValue;
                    $row[$fieldName . '_normalized'] = ($minValueFloat != 0 && $currentValue != 0) ? $minValueFloat / $currentValue : 0;
                }
            } else {
                // For benefit criteria: value/max
                $maxValue = max($values);
                foreach ($normalizedMatrix as &$row) {
                    $currentValue = (float) $row[$fieldName];
                    $maxValueFloat = (float) $maxValue;
                    $row[$fieldName . '_normalized'] = $maxValueFloat != 0 ? $currentValue / $maxValueFloat : 0;
                }
            }
        }

        return $normalizedMatrix;
    }
    /**
     * Calculate final scores using weighted sum
     */
    private function calculateScores(array $normalizedMatrix, $kriteria): array
    {
        $scores = [];

        foreach ($normalizedMatrix as $index => $row) {
            $score = 0;

            foreach ($kriteria as $kriteriaItem) {
                $fieldName = strtolower($kriteriaItem->kode); // c1, c2, c3, etc.
                $weight = (float) $kriteriaItem->bobot;
                $normalizedValue = $row[$fieldName . '_normalized'] ?? 0;
                $score += $normalizedValue * $weight;
            }

            $scores[$index] = $score;
        }

        return $scores;
    }
    /**
     * Create ranking based on scores
     */
    private function createRanking($calonMahasiswa, array $scores, array $normalizedMatrix): array
    {
        $kriteria = Kriteria::orderBy('kode')->get();
        $ranking = [];

        foreach ($calonMahasiswa as $index => $calon) {
            $row = [
                'calon_mahasiswa_id' => $calon->id,
                'kode' => $calon->kode,
                'nama' => $calon->nama,
                'skor' => $scores[$index],
            ];

            // Add dynamic normalized values
            foreach ($kriteria as $k) {
                $fieldName = strtolower($k->kode); // c1, c2, c3, etc.
                $row[$fieldName . '_normalized'] = $normalizedMatrix[$index][$fieldName . '_normalized'] ?? 0;
            }

            $ranking[] = $row;
        }

        // Sort by score descending
        usort($ranking, function ($a, $b) {
            return $b['skor'] <=> $a['skor'];
        });

        // Add rank
        foreach ($ranking as $index => &$item) {
            $item['rank'] = $index + 1;
        }

        return $ranking;
    }
    /**
     * Save results to database
     */
    private function saveResults(array $ranking): void
    {
        $kriteria = Kriteria::orderBy('kode')->get();

        // Clear existing results
        HasilSeleksi::truncate();

        // Get kuota beasiswa - assume 5 as default
        $kuotaBeasiswa = 5; // You can make this configurable later

        // Save new results
        foreach ($ranking as $item) {
            $status = $item['rank'] <= $kuotaBeasiswa ? 'diterima' : 'ditolak';
            $resultData = [
                'calon_mahasiswa_id' => $item['calon_mahasiswa_id'],
                'skor' => $item['skor'],
                'rank' => $item['rank'],
                'ranking' => $item['rank'], // For Filament table
                'status' => $status,
                'tanggal_seleksi' => now()->toDateString(),
            ];

            // Prepare normalized values for JSON storage
            $normalizedValues = [];

            // Add dynamic normalized values to both individual columns and JSON
            foreach ($kriteria as $k) {
                $fieldName = strtolower($k->kode); // c1, c2, c3, etc.
                $normalizedFieldName = $fieldName . '_normalized';
                $normalizedValue = $item[$normalizedFieldName] ?? 0;

                // Store in individual columns only if they exist (for backward compatibility)
                $columnExists = Schema::hasColumn('hasil_seleksi', $normalizedFieldName);
                if ($columnExists) {
                    $resultData[$normalizedFieldName] = $normalizedValue;
                }

                // Always store in JSON (for complete flexibility)
                $normalizedValues[$fieldName] = $normalizedValue;
            }

            // Add JSON field
            $resultData['normalized_values'] = $normalizedValues;

            HasilSeleksi::create($resultData);
        }
    }

    /**
     * Get latest SAW results
     */
    public function getLatestResults()
    {
        return HasilSeleksi::with('calonMahasiswa')
            ->orderBy('rank')
            ->get();
    }

    /**
     * Check if SAW calculation results exist
     */
    public function hasResults(): bool
    {
        return HasilSeleksi::exists();
    }

    /**
     * Get results for charts/graphs
     */
    public function getResultsForChart()
    {
        $results = $this->getLatestResults();
        return $results->map(function ($result) {
            return [
                'nama' => $result->calonMahasiswa->nama,
                'skor' => round((float) $result->skor, 4),
                'rank' => $result->rank,
            ];
        })->toArray();
    }
    /**
     * Update status beasiswa berdasarkan kuota
     */
    public function updateStatusByKuota(int $kuotaBeasiswa): void
    {
        // Validasi kuota
        $totalPendaftar = CalonMahasiswa::count();
        $totalHasilSeleksi = HasilSeleksi::count();

        if ($kuotaBeasiswa > $totalPendaftar) {
            throw new \InvalidArgumentException("Kuota beasiswa ({$kuotaBeasiswa}) tidak boleh melebihi total pendaftar ({$totalPendaftar}).");
        }

        if ($kuotaBeasiswa > $totalHasilSeleksi) {
            throw new \InvalidArgumentException("Kuota beasiswa ({$kuotaBeasiswa}) tidak boleh melebihi total hasil seleksi ({$totalHasilSeleksi}).");
        }

        if ($kuotaBeasiswa < 1) {
            throw new \InvalidArgumentException("Kuota beasiswa minimal adalah 1 mahasiswa.");
        }

        // Reset semua status jadi ditolak dulu
        HasilSeleksi::query()->update(['status' => 'ditolak']);

        // Update yang masuk kuota jadi diterima berdasarkan ranking
        HasilSeleksi::orderBy('ranking')
            ->limit($kuotaBeasiswa)
            ->update(['status' => 'diterima']);
    }
}