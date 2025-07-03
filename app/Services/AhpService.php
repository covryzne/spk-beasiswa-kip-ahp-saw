<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\MatriksAhp;
use App\Models\PerhitunganAhp;

class AhpService
{
    /**
     * Calculate AHP weights and consistency
     */
    public function calculateAhp(): array
    {
        $kriteria = Kriteria::orderBy('kode')->get();
        $n = $kriteria->count();

        if ($n === 0) {
            throw new \Exception('Tidak ada kriteria yang tersedia');
        }

        // Get comparison matrix
        $matrix = $this->getComparisonMatrix($kriteria);

        // Normalize matrix
        $normalizedMatrix = $this->normalizeMatrix($matrix);

        // Calculate priority weights (eigen vector)
        $weights = $this->calculateWeights($normalizedMatrix);

        // Calculate consistency
        $consistency = $this->calculateConsistency($matrix, $weights, $n);

        // Update kriteria weights
        $this->updateKriteriaWeights($kriteria, $weights);

        // Save calculation results
        $this->savePerhitunganAhp($consistency, $weights, $normalizedMatrix);

        return [
            'matrix' => $matrix,
            'normalized_matrix' => $normalizedMatrix,
            'weights' => $weights,
            'lambda_max' => $consistency['lambda_max'],
            'ci' => $consistency['ci'],
            'ri' => $consistency['ri'],
            'cr' => $consistency['cr'],
            'is_consistent' => $consistency['is_consistent'],
        ];
    }

    /**
     * Get comparison matrix from database
     */
    private function getComparisonMatrix($kriteria): array
    {
        $n = $kriteria->count();
        $matrix = [];

        for ($i = 0; $i < $n; $i++) {
            $matrix[$i] = [];
            for ($j = 0; $j < $n; $j++) {
                if ($i === $j) {
                    $matrix[$i][$j] = 1;
                } else {
                    $matriksAhp = MatriksAhp::where('kriteria_1_id', $kriteria[$i]->id)
                        ->where('kriteria_2_id', $kriteria[$j]->id)
                        ->first();

                    $matrix[$i][$j] = $matriksAhp ? (float) $matriksAhp->nilai : 1;
                }
            }
        }

        return $matrix;
    }

    /**
     * Normalize comparison matrix
     */
    private function normalizeMatrix(array $matrix): array
    {
        $n = count($matrix);
        $normalizedMatrix = [];

        // Calculate column sums
        $columnSums = [];
        for ($j = 0; $j < $n; $j++) {
            $sum = 0;
            for ($i = 0; $i < $n; $i++) {
                $sum += $matrix[$i][$j];
            }
            $columnSums[$j] = $sum;
        }

        // Normalize each element
        for ($i = 0; $i < $n; $i++) {
            $normalizedMatrix[$i] = [];
            for ($j = 0; $j < $n; $j++) {
                $normalizedMatrix[$i][$j] = $columnSums[$j] != 0 ?
                    $matrix[$i][$j] / $columnSums[$j] : 0;
            }
        }

        return $normalizedMatrix;
    }

    /**
     * Calculate priority weights (average of each row)
     */
    private function calculateWeights(array $normalizedMatrix): array
    {
        $n = count($normalizedMatrix);
        $weights = [];

        for ($i = 0; $i < $n; $i++) {
            $sum = 0;
            for ($j = 0; $j < $n; $j++) {
                $sum += $normalizedMatrix[$i][$j];
            }
            $weights[$i] = $sum / $n;
        }

        return $weights;
    }

    /**
     * Calculate consistency ratio (CR) - Following Excel calculation method
     */
    private function calculateConsistency(array $matrix, array $weights, int $n): array
    {
        // Step 3: Calculate weighted sum vector (Matrix × Priority Vector)
        // This is the "Penjumlahan setiap baris" step from Excel
        $weightedSum = [];
        for ($i = 0; $i < $n; $i++) {
            $sum = 0;
            for ($j = 0; $j < $n; $j++) {
                $sum += $matrix[$i][$j] * $weights[$j];
            }
            $weightedSum[$i] = $sum;
        }

        // Step 4: Calculate lambda max (Average of weighted_sum[i] / weights[i])
        $lambdaMax = 0;
        for ($i = 0; $i < $n; $i++) {
            if ($weights[$i] != 0) {
                $lambdaMax += $weightedSum[$i] / $weights[$i];
            }
        }
        $lambdaMax = $lambdaMax / $n;

        // Step 5: Calculate CI (Consistency Index) = (lambda_max - n) / (n - 1)
        $ci = ($lambdaMax - $n) / ($n - 1);

        // Step 6: Get RI (Random Index) based on matrix size
        $ri = $this->getRandomIndex($n);

        // Step 7: Calculate CR (Consistency Ratio) = CI / RI
        $cr = $ri != 0 ? $ci / $ri : 0;

        return [
            'lambda_max' => $lambdaMax,
            'ci' => $ci,
            'ri' => $ri,
            'cr' => $cr,
            'weighted_sum' => $weightedSum, // Add this for debugging
            'is_consistent' => $cr < 0.1,
        ];
    }

    /**
     * Get Random Index (RI) based on matrix size
     * Standard RI values for AHP
     */
    private function getRandomIndex(int $n): float
    {
        $riValues = [
            1 => 0.00,
            2 => 0.00,
            3 => 0.58,
            4 => 0.90,
            5 => 1.12,
            6 => 1.24,
            7 => 1.32,
            8 => 1.41,
            9 => 1.45,
            10 => 1.49,
            11 => 1.51,
            12 => 1.48,
            13 => 1.56,
            14 => 1.57,
            15 => 1.59,
        ];

        return $riValues[$n] ?? 1.59;
    }

    /**
     * Update kriteria weights in database
     */
    private function updateKriteriaWeights($kriteria, array $weights): void
    {
        foreach ($kriteria as $index => $k) {
            $k->update(['bobot' => $weights[$index]]);
        }
    }

    /**
     * Save calculation results to database
     */
    private function savePerhitunganAhp(array $consistency, array $weights, array $normalizedMatrix): void
    {
        // Clear previous calculations first
        PerhitunganAhp::truncate();

        PerhitunganAhp::create([
            'lambda_max' => $consistency['lambda_max'],
            'ci' => $consistency['ci'],
            'ri' => $consistency['ri'],
            'cr' => $consistency['cr'],
            'is_consistent' => $consistency['is_consistent'],
            'eigen_vector' => $weights,
            'matriks_normalized' => $normalizedMatrix,
            'weighted_sum' => $consistency['weighted_sum'] ?? [], // Include weighted sum for verification
            'tanggal_perhitungan' => now()->toDateString(),
        ]);
    }

    /**
     * Get latest AHP calculation results
     */
    public function getLatestCalculation(): ?PerhitunganAhp
    {
        return PerhitunganAhp::latest()->first();
    }

    /**
     * Check if AHP calculation exists and is consistent
     */
    public function isCalculationValid(): bool
    {
        $latest = $this->getLatestCalculation();
        return $latest && $latest->is_consistent;
    }

    /**
     * Debug method to verify AHP calculation step by step
     * Compare with Excel calculation
     */
    public function debugAhpCalculation(): array
    {
        $kriteria = Kriteria::orderBy('kode')->get();
        $n = $kriteria->count();

        if ($n === 0) {
            throw new \Exception('Tidak ada kriteria yang tersedia');
        }

        // Step 1: Get comparison matrix
        $matrix = $this->getComparisonMatrix($kriteria);

        // Step 2: Calculate column sums (for normalization)
        $columnSums = [];
        for ($j = 0; $j < $n; $j++) {
            $sum = 0;
            for ($i = 0; $i < $n; $i++) {
                $sum += $matrix[$i][$j];
            }
            $columnSums[$j] = $sum;
        }

        // Step 3: Normalize matrix
        $normalizedMatrix = $this->normalizeMatrix($matrix);

        // Step 4: Calculate weights (priority vector)
        $weights = $this->calculateWeights($normalizedMatrix);

        // Step 5: Calculate weighted sum vector
        $weightedSum = [];
        for ($i = 0; $i < $n; $i++) {
            $sum = 0;
            for ($j = 0; $j < $n; $j++) {
                $sum += $matrix[$i][$j] * $weights[$j];
            }
            $weightedSum[$i] = $sum;
        }

        // Step 6: Calculate lambda max
        $lambdaMax = 0;
        for ($i = 0; $i < $n; $i++) {
            if ($weights[$i] != 0) {
                $lambdaMax += $weightedSum[$i] / $weights[$i];
            }
        }
        $lambdaMax = $lambdaMax / $n;

        // Step 7: Calculate CI and CR
        $ci = ($lambdaMax - $n) / ($n - 1);
        $ri = $this->getRandomIndex($n);
        $cr = $ri != 0 ? $ci / $ri : 0;

        return [
            'step_1_matrix' => $matrix,
            'step_2_column_sums' => $columnSums,
            'step_3_normalized_matrix' => $normalizedMatrix,
            'step_4_weights' => $weights,
            'step_5_weighted_sum' => $weightedSum,
            'step_6_lambda_max' => $lambdaMax,
            'step_7_ci' => $ci,
            'step_7_ri' => $ri,
            'step_7_cr' => $cr,
            'is_consistent' => $cr < 0.1,
            'criteria_labels' => $kriteria->pluck('nama', 'kode')->toArray(),
        ];
    }
}
