<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use App\Services\SawService;
use App\Services\AhpService;
use App\Models\CalonMahasiswa;
use App\Models\Kriteria;
use App\Models\HasilSeleksi;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class PerhitunganSaw extends Page implements HasActions
{
    use InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $slug = 'perhitungan-saw';

    protected static string $view = 'filament.admin.pages.perhitungan-saw';

    protected static ?string $navigationLabel = 'Seleksi';

    protected static ?string $title = 'Perhitungan SAW';

    protected static ?string $navigationGroup = 'Metode SAW';

    protected static ?int $navigationSort = 5;

    public $isAhpReady = false;
    public $isCalculated = false;

    // Step-by-step SAW calculation data
    public $kriteria = [];
    public $calonMahasiswa = [];
    public $bobotAhp = [];
    public $decisionMatrix = [];
    public $normalizedMatrix = [];
    public $finalScores = [];
    public $consistencyCheck = [];

    // Control visibility of steps
    public $showDecisionMatrix = false;
    public $showNormalization = false;
    public $showScoring = false;
    public $showResults = false;
    public function mount(): void
    {
        $this->kriteria = Kriteria::orderBy('kode')->get()->toArray();
        $this->calonMahasiswa = CalonMahasiswa::orderBy('kode')->get()->toArray();

        // Check if AHP calculation is ready
        $ahpService = new AhpService();
        $this->isAhpReady = $ahpService->isCalculationValid();

        if ($this->isAhpReady) {
            $this->loadBobotAhp();
        }

        // Check if SAW has been calculated
        $sawService = new SawService();
        if ($sawService->hasResults()) {
            $this->isCalculated = true;
            // Only load calculation steps if we have data to work with
            if (count($this->calonMahasiswa) > 0 && count($this->kriteria) > 0) {
                $this->loadCalculationSteps();
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('calculate')
                ->label('Hitung SAW')
                ->icon('heroicon-o-calculator')
                ->color('success')
                ->disabled(!$this->isAhpReady)
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Perhitungan SAW')
                ->modalDescription('Perhitungan akan menggunakan bobot dari AHP dan data calon mahasiswa untuk menghasilkan ranking final.')
                ->action(function () {
                    try {
                        $sawService = new SawService();
                        $results = $sawService->calculateSaw();
                        $this->isCalculated = true;
                        $this->loadCalculationSteps();

                        Notification::make()
                            ->title('Perhitungan SAW Berhasil')
                            ->body('Proses seleksi telah selesai. Silakan lihat hasil di menu "Hasil Seleksi".')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error Perhitungan SAW')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('reset')
                ->label('Reset Perhitungan')
                ->icon('heroicon-o-arrow-path')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Reset Perhitungan SAW')
                ->modalDescription('Ini akan menghapus semua hasil perhitungan SAW dan step-by-step calculation.')
                ->action(function () {
                    HasilSeleksi::truncate();

                    $this->isCalculated = false;
                    $this->showDecisionMatrix = false;
                    $this->showNormalization = false;
                    $this->showScoring = false;
                    $this->showResults = false;
                    $this->decisionMatrix = [];
                    $this->normalizedMatrix = [];
                    $this->finalScores = [];

                    Notification::make()
                        ->title('Reset Berhasil')
                        ->body('Semua hasil perhitungan SAW telah dihapus.')
                        ->success()
                        ->send();
                }),
        ];
    }

    private function loadBobotAhp(): void
    {
        $kriteria = Kriteria::orderBy('kode')->get();
        $this->bobotAhp = $kriteria->pluck('bobot', 'kode')->toArray();
    }
    private function loadCalculationSteps(): void
    {
        if (!$this->isAhpReady) return;

        // Load existing calculation results
        $calonMahasiswa = CalonMahasiswa::orderBy('kode')->get();
        $kriteria = Kriteria::orderBy('kode')->get();

        // Validate data exists
        if ($calonMahasiswa->isEmpty() || $kriteria->isEmpty()) {
            return;
        }

        // Build decision matrix dynamically based on existing criteria
        $this->decisionMatrix = [];
        foreach ($calonMahasiswa as $calon) {
            $row = [];
            foreach ($kriteria as $k) {
                $fieldName = strtolower($k->kode); // c1, c2, c3, etc.
                $row[$k->kode] = (float) ($calon->{$fieldName} ?? 0);
            }
            $this->decisionMatrix[$calon->kode] = $row;
        }

        // Only proceed if we have valid data
        if (empty($this->decisionMatrix)) {
            return;
        }

        // Calculate normalization
        $this->calculateNormalization();

        // Calculate final scores
        $this->calculateFinalScores();

        // Show all steps
        $this->showDecisionMatrix = true;
        $this->showNormalization = true;
        $this->showScoring = true;
        $this->showResults = true;
    }
    private function calculateNormalization(): void
    {
        $kriteria = Kriteria::orderBy('kode')->get();
        $this->normalizedMatrix = [];

        foreach ($this->decisionMatrix as $kode => $values) {
            $this->normalizedMatrix[$kode] = [];

            foreach ($kriteria as $k) {
                $column = $k->kode;
                $allValues = collect($this->decisionMatrix)->pluck($column)->toArray();

                if ($k->jenis === 'benefit') {
                    // For benefit criteria: Rij = Xij / max(Xij)
                    $maxValue = max($allValues);
                    if ($maxValue > 0) {
                        $this->normalizedMatrix[$kode][$column] = $values[$column] / $maxValue;
                    } else {
                        $this->normalizedMatrix[$kode][$column] = 0;
                    }
                } else {
                    // For cost criteria: Rij = min(Xij) / Xij
                    $minValue = min($allValues);
                    if ($values[$column] > 0 && $minValue > 0) {
                        $this->normalizedMatrix[$kode][$column] = $minValue / $values[$column];
                    } else {
                        $this->normalizedMatrix[$kode][$column] = 0;
                    }
                }
            }
        }
    }

    private function calculateFinalScores(): void
    {
        $this->finalScores = [];

        foreach ($this->normalizedMatrix as $kode => $normalizedValues) {
            $score = 0;
            foreach ($normalizedValues as $kriteriaKode => $normalizedValue) {
                $bobot = $this->bobotAhp[$kriteriaKode] ?? 0;
                $score += $bobot * $normalizedValue;
            }
            $this->finalScores[$kode] = $score;
        }

        // Sort by score descending
        arsort($this->finalScores);
    }

    public function toggleStep(string $step): void
    {
        match ($step) {
            'decision' => $this->showDecisionMatrix = !$this->showDecisionMatrix,
            'normalization' => $this->showNormalization = !$this->showNormalization,
            'scoring' => $this->showScoring = !$this->showScoring,
            'results' => $this->showResults = !$this->showResults,
        };
    }

    public function getTitle(): string
    {
        return 'Proses Seleksi dengan Metode SAW';
    }
}
