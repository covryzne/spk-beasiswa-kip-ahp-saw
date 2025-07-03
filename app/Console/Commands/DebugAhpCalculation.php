<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AhpService;
use App\Models\Kriteria;
use App\Models\MatriksAhp;

class DebugAhpCalculation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:ahp {--compare : Compare with Excel values}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug AHP calculation step by step to verify against Excel';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 AHP CALCULATION DEBUG');
        $this->info('=========================');

        try {
            $ahpService = new AhpService();
            $debug = $ahpService->debugAhpCalculation();

            // Step 1: Show comparison matrix
            $this->newLine();
            $this->info('📊 STEP 1: Comparison Matrix (Matriks Perbandingan Berpasangan)');
            $this->showMatrix($debug['step_1_matrix'], $debug['criteria_labels']);

            // Step 2: Show column sums
            $this->newLine();
            $this->info('➕ STEP 2: Column Sums (Jumlah Kolom)');
            $this->showArray($debug['step_2_column_sums']);

            // Step 3: Show normalized matrix
            $this->newLine();
            $this->info('📋 STEP 3: Normalized Matrix (Matriks Normalisasi)');
            $this->showMatrix($debug['step_3_normalized_matrix'], $debug['criteria_labels']);

            // Step 4: Show weights (priority vector)
            $this->newLine();
            $this->info('⚖️ STEP 4: Priority Weights (Bobot Prioritas)');
            $this->showWeights($debug['step_4_weights'], $debug['criteria_labels']);

            // Step 5: Show weighted sum
            $this->newLine();
            $this->info('🔢 STEP 5: Weighted Sum (Penjumlahan Setiap Baris)');
            $this->showArray($debug['step_5_weighted_sum']);

            // Step 6-7: Show final results
            $this->newLine();
            $this->info('🎯 STEP 6-7: Final Results');
            $this->table(
                ['Metric', 'Value'],
                [
                    ['Lambda Max', number_format($debug['step_6_lambda_max'], 9)],
                    ['CI', number_format($debug['step_7_ci'], 9)],
                    ['RI', number_format($debug['step_7_ri'], 9)],
                    ['CR', number_format($debug['step_7_cr'], 9)],
                    ['Consistency', $debug['is_consistent'] ? '✅ Konsisten' : '❌ Tidak Konsisten'],
                ]
            );

            // Compare with Excel if flag is set
            if ($this->option('compare')) {
                $this->compareWithExcel($debug);
            }
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function showMatrix(array $matrix, array $labels): void
    {
        $headers = array_merge(['Kriteria'], array_values($labels));
        $rows = [];

        foreach ($matrix as $i => $row) {
            $rowData = [array_values($labels)[$i]];
            foreach ($row as $value) {
                $rowData[] = number_format($value, 6);
            }
            $rows[] = $rowData;
        }

        $this->table($headers, $rows);
    }

    private function showArray(array $data): void
    {
        $kriteria = Kriteria::orderBy('kode')->get();
        $rows = [];

        foreach ($data as $i => $value) {
            $rows[] = [
                $kriteria[$i]->nama ?? "C" . ($i + 1),
                number_format($value, 9)
            ];
        }

        $this->table(['Kriteria', 'Value'], $rows);
    }

    private function showWeights(array $weights, array $labels): void
    {
        $rows = [];
        foreach ($weights as $i => $weight) {
            $rows[] = [
                array_values($labels)[$i],
                number_format($weight, 6)
            ];
        }

        $this->table(['Kriteria', 'Weight'], $rows);
    }

    private function compareWithExcel(array $debug): void
    {
        $this->newLine();
        $this->info('📊 COMPARISON WITH EXCEL VALUES');
        $this->info('================================');

        // Excel values from your calculation
        $excelValues = [
            'lambda_max' => 5.041892131,
            'ci' => 0.010473033,
            'cr' => 0.009350922,
            'weights' => [0.40173, 0.24420, 0.13733, 0.13733, 0.07941]
        ];

        $this->table(
            ['Metric', 'System', 'Excel', 'Difference', 'Status'],
            [
                [
                    'Lambda Max',
                    number_format($debug['step_6_lambda_max'], 9),
                    number_format($excelValues['lambda_max'], 9),
                    number_format(abs($debug['step_6_lambda_max'] - $excelValues['lambda_max']), 9),
                    abs($debug['step_6_lambda_max'] - $excelValues['lambda_max']) < 0.001 ? '✅' : '❌'
                ],
                [
                    'CI',
                    number_format($debug['step_7_ci'], 9),
                    number_format($excelValues['ci'], 9),
                    number_format(abs($debug['step_7_ci'] - $excelValues['ci']), 9),
                    abs($debug['step_7_ci'] - $excelValues['ci']) < 0.001 ? '✅' : '❌'
                ],
                [
                    'CR',
                    number_format($debug['step_7_cr'], 9),
                    number_format($excelValues['cr'], 9),
                    number_format(abs($debug['step_7_cr'] - $excelValues['cr']), 9),
                    abs($debug['step_7_cr'] - $excelValues['cr']) < 0.001 ? '✅' : '❌'
                ]
            ]
        );

        $this->newLine();
        $this->info('⚖️ WEIGHTS COMPARISON');
        $weightRows = [];
        foreach ($debug['step_4_weights'] as $i => $weight) {
            $excelWeight = $excelValues['weights'][$i] ?? 0;
            $weightRows[] = [
                "C" . ($i + 1),
                number_format($weight, 6),
                number_format($excelWeight, 6),
                number_format(abs($weight - $excelWeight), 6),
                abs($weight - $excelWeight) < 0.001 ? '✅' : '❌'
            ];
        }

        $this->table(['Kriteria', 'System', 'Excel', 'Difference', 'Status'], $weightRows);
    }
}
