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
     * Calculate consistency ratio (CR)
     */
    private function calculateConsistency(array $matrix, array $weights, int $n): array
    {
        // Calculate weighted sum vector
        $weightedSum = [];
        for ($i = 0; $i < $n; $i++) {
            $sum = 0;
            for ($j = 0; $j < $n; $j++) {
                $sum += $matrix[$i][$j] * $weights[$j];
            }
            $weightedSum[$i] = $sum;
        }

        // Calculate lambda max
        $lambdaMax = 0;
        for ($i = 0; $i < $n; $i++) {
            if ($weights[$i] != 0) {
                $lambdaMax += $weightedSum[$i] / $weights[$i];
            }
        }
        $lambdaMax = $lambdaMax / $n;

        // Calculate CI (Consistency Index)
        $ci = ($lambdaMax - $n) / ($n - 1);

        // Get RI (Random Index)
        $ri = PerhitunganAhp::getRiForSize($n);

        // Calculate CR (Consistency Ratio)
        $cr = $ri != 0 ? $ci / $ri : 0;

        return [
            'lambda_max' => $lambdaMax,
            'ci' => $ci,
            'ri' => $ri,
            'cr' => $cr,
            'is_consistent' => $cr < 0.1,
        ];
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
        PerhitunganAhp::create([
            'lambda_max' => $consistency['lambda_max'],
            'ci' => $consistency['ci'],
            'ri' => $consistency['ri'],
            'cr' => $consistency['cr'],
            'is_consistent' => $consistency['is_consistent'],
            'eigen_vector' => $weights,
            'matriks_normalized' => $normalizedMatrix,
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
}
