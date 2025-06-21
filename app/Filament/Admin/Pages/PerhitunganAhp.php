<?php
// app/Filament/Admin/Pages/PerhitunganAhp.php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use App\Services\AhpService;
use App\Models\Kriteria;
use App\Models\MatriksAhp;
use App\Models\HasilSeleksi;

class PerhitunganAhp extends Page implements HasActions
{
    use InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static string $view = 'filament.admin.pages.bobot-ahp';
    protected static ?string $title = 'Perhitungan Bobot AHP';
    protected static ?string $navigationLabel = 'Perhitungan Bobot';
    protected static ?string $navigationGroup = 'Metode AHP';
    protected static ?int $navigationSort = 3;

    public ?array $data = [];
    public ?array $matriksData = [];
    public ?array $bobotResults = [];
    public ?float $consistencyRatio = null;
    public ?array $matrixNormalisasi = null;
    public ?array $weightedSum = null;
    public ?float $lambdaMax = null;
    public ?float $consistencyIndex = null;
    public ?float $randomIndex = null;

    // Livewire listeners
    protected $listeners = [
        'matrix-updated' => '$refresh',
        'refresh-page' => '$refresh'
    ];
    public function mount(): void
    {
        $this->loadMatriksData();
        $this->loadPreviousResults();
        $this->refreshKriteriaTable();
    }

    private function loadPreviousResults(): void
    {
        // Load hasil perhitungan AHP terakhir
        $previousCalc = \App\Models\PerhitunganAhp::latest()->first();

        if ($previousCalc) {
            // Load bobot dari eigen_vector
            $eigenVector = $previousCalc->eigen_vector;
            if (is_array($eigenVector) && count($eigenVector) >= 5) {
                $kriteria = Kriteria::orderBy('kode')->pluck('kode')->toArray();
                $this->bobotResults = [];

                // Map eigen vector to criteria codes dynamically
                foreach ($kriteria as $index => $kode) {
                    if (isset($eigenVector[$index])) {
                        $this->bobotResults[$kode] = $eigenVector[$index];
                    }
                }
            }

            $this->consistencyRatio = (float) $previousCalc->cr;
            $this->lambdaMax = (float) $previousCalc->lambda_max;
            $this->consistencyIndex = (float) $previousCalc->ci;
            $this->randomIndex = (float) $previousCalc->ri;

            // Set hasil matrix normalisasi dari database
            if ($previousCalc->matriks_normalized) {
                $this->matrixNormalisasi = $previousCalc->matriks_normalized;
            }

            // Load weighted sum dari database
            if ($previousCalc->weighted_sum && is_array($previousCalc->weighted_sum)) {
                $this->weightedSum = $previousCalc->weighted_sum;
            }

            // Update bobot di tabel kriteria juga
            $this->updateKriteriaWeights();

            // Jika weighted sum belum ada atau data tidak lengkap, recalculate
            if (empty($this->weightedSum) && !empty($this->bobotResults)) {
                $this->recalculateWeightedSum();
            }
        } else {
            // No previous calculation found - ensure all variables are reset
            $this->bobotResults = [];
            $this->consistencyRatio = null;
            $this->matrixNormalisasi = null;
            $this->weightedSum = null;
            $this->lambdaMax = null;
            $this->consistencyIndex = null;
            $this->randomIndex = null;
        }
    }
    private function recalculateWeightedSum(): void
    {
        // Rebuild weighted sum dari data yang ada
        $kriteria = Kriteria::orderBy('kode')->get();

        if ($kriteria->count() >= 5 && !empty($this->bobotResults)) {
            $matrix = [];

            // Build matrix dari database MatriksAhp
            $matriksAhp = MatriksAhp::all()->keyBy(function ($item) {
                return $item->kriteria_1_id . '_' . $item->kriteria_2_id;
            });

            foreach ($kriteria as $i => $kriteriaI) {
                $matrix[$i] = [];
                foreach ($kriteria as $j => $kriteriaJ) {
                    if ($i == $j) {
                        $matrix[$i][$j] = 1;
                    } else {
                        $matriksKey = $kriteriaI->id . '_' . $kriteriaJ->id;
                        $matrix[$i][$j] = isset($matriksAhp[$matriksKey]) ? (float)$matriksAhp[$matriksKey]->nilai : 1;
                    }
                }
            }

            // Calculate weighted sum
            $this->calculateWeightedSum($matrix, $this->bobotResults);
        }
    }

    private function updateKriteriaWeights(): void
    {
        if (!empty($this->bobotResults)) {
            $kriteria = Kriteria::orderBy('kode')->get();

            foreach ($kriteria as $index => $k) {
                $kode = $k->kode;
                if (isset($this->bobotResults[$kode])) {
                    $k->bobot = $this->bobotResults[$kode];
                    $k->save();
                }
            }
        }
    }
    private function refreshKriteriaTable(): void
    {
        // Force refresh kriteria table dengan bobot terbaru
        $kriteria = Kriteria::orderBy('kode')->get();

        // Check if we have bobot results from previous calculation
        if (!empty($this->bobotResults)) {
            foreach ($kriteria as $k) {
                $kode = $k->kode;
                if (isset($this->bobotResults[$kode])) {
                    $k->bobot = $this->bobotResults[$kode];
                    $k->save();
                }
            }
        }
    }

    public function form(Form $form): Form
    {
        // Tidak menggunakan form Filament, gunakan custom matrix view
        return $form->schema([]);
    }

    protected function getMatriksFormSchema(): array
    {
        // Tidak digunakan lagi karena menggunakan custom matrix view
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('gunakanRekomendasi')
                ->label('Gunakan Rekomendasi')
                ->icon('heroicon-o-light-bulb')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Gunakan Nilai Rekomendasi')
                ->modalDescription('Ini akan mengisi matrix dengan nilai rekomendasi default berdasarkan konteks beasiswa KIP Kuliah. Anda tetap bisa mengubahnya setelah itu.')
                ->action('useRecommendedValues'),
            Action::make('hitungBobot')
                ->label('Hitung Bobot AHP')
                ->color('primary')
                ->icon('heroicon-o-calculator')
                ->action('calculateAhp'),
            $this->resetMatrixAction(), // Tambahkan action reset matrix
        ];
    }

    public function useRecommendedValues(): void
    {
        $kriteria = Kriteria::orderBy('kode')->get();
        $recommendations = [
            'C1_C2' => 3,
            'C1_C3' => 5,
            'C1_C4' => 3,
            'C1_C5' => 3,
            'C2_C3' => 2,
            'C2_C4' => 1,
            'C2_C5' => 2,
            'C3_C4' => 1,
            'C3_C5' => 1,
            'C4_C5' => 1,
        ];

        foreach ($kriteria as $i => $kriteriaI) {
            foreach ($kriteria as $j => $kriteriaJ) {
                if ($i < $j) {
                    $key = "matriks_{$kriteriaI->id}_{$kriteriaJ->id}";
                    $recKey = $kriteriaI->kode . '_' . $kriteriaJ->kode;
                    $nilai = $recommendations[$recKey] ?? 1;

                    $this->data[$key] = $nilai;

                    // Update reciprocal
                    $reciprocalKey = "matriks_{$kriteriaJ->id}_{$kriteriaI->id}";
                    $this->data[$reciprocalKey] = 1 / $nilai;

                    // Auto-save langsung
                    $this->autoSaveMatrix($kriteriaI, $kriteriaJ, $nilai);
                }
            }
        }

        Notification::make()
            ->title('Nilai Rekomendasi Diterapkan')
            ->body('Matrix telah diisi dengan nilai rekomendasi dan otomatis disimpan.')
            ->success()
            ->send();
    }

    public function saveMatrix(): void
    {
        try {
            $kriteria = Kriteria::orderBy('kode')->get();
            $saved = 0;

            foreach ($kriteria as $i => $kriteriaI) {
                foreach ($kriteria as $j => $kriteriaJ) {
                    if ($i < $j) { // Hanya simpan setengah matriks (upper triangle)
                        $key = "matriks_{$kriteriaI->id}_{$kriteriaJ->id}";
                        $nilai = (float) ($this->data[$key] ?? 1);

                        if ($nilai <= 0) {
                            $nilai = 1;
                        }

                        MatriksAhp::updateOrCreate(
                            [
                                'kriteria_1_id' => $kriteriaI->id,
                                'kriteria_2_id' => $kriteriaJ->id,
                            ],
                            ['nilai' => $nilai]
                        );

                        // Simpan kebalikannya juga
                        MatriksAhp::updateOrCreate(
                            [
                                'kriteria_1_id' => $kriteriaJ->id,
                                'kriteria_2_id' => $kriteriaI->id,
                            ],
                            ['nilai' => round(1 / $nilai, 6)]
                        );

                        $saved++;
                    }
                }
            }

            Notification::make()
                ->title('Matriks Berhasil Disimpan')
                ->body("Data matriks perbandingan ({$saved} pasang) telah disimpan ke database.")
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Menyimpan Matriks')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function calculateAhp(): void
    {
        try {
            // Validasi apakah matrix sudah terisi lengkap
            if (!$this->validateMatrix()) {
                Notification::make()
                    ->title('Matrix Belum Lengkap')
                    ->body('Mohon isi semua perbandingan berpasangan terlebih dahulu.')
                    ->warning()
                    ->send();
                return;
            }

            $ahpService = new AhpService();
            $result = $ahpService->calculateAhp();

            // Set semua data untuk step-by-step breakdown
            $this->bobotResults = $result['weights'];
            $this->consistencyRatio = $result['cr'];
            $this->matrixNormalisasi = $result['normalized_matrix'];
            $this->lambdaMax = $result['lambda_max'];
            $this->consistencyIndex = $result['ci'];
            $this->randomIndex = $result['ri'];

            // Calculate weighted sum for step 3
            $this->calculateWeightedSum($result['matrix'], $result['weights']);

            if ($result['cr'] <= 0.1) {
                Notification::make()
                    ->title('Perhitungan AHP Berhasil')
                    ->body('Consistency Ratio: ' . round($result['cr'], 4) . ' (Konsisten)')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Peringatan: Consistency Ratio Tinggi')
                    ->body('CR: ' . round($result['cr'], 4) . ' > 0.1. Mohon perbaiki matriks.')
                    ->warning()
                    ->send();
            }

            // PENTING: Invalidate SAW results karena bobot AHP berubah
            $this->invalidateSawResults();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Perhitungan AHP')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    private function invalidateSawResults(): void
    {
        // Hapus hasil SAW lama karena bobot AHP berubah
        \App\Models\HasilSeleksi::truncate();

        // Optional: Bisa tambah log atau notification
        // Notification::make()
        //     ->title('SAW Results Invalidated')
        //     ->body('Hasil SAW dihapus karena bobot AHP berubah. Silakan hitung ulang di menu Proses Seleksi.')
        //     ->info()
        //     ->send();
    }

    private function calculateWeightedSum(array $matrix, array $weights): void
    {
        $n = count($matrix);
        $this->weightedSum = [];

        // Convert associative array to indexed array
        $weightValues = array_values($weights);

        for ($i = 0; $i < $n; $i++) {
            $sum = 0;
            for ($j = 0; $j < $n; $j++) {
                $sum += $matrix[$i][$j] * $weightValues[$j];
            }
            $this->weightedSum[$i] = $sum;
        }
    }

    private function validateMatrix(): bool
    {
        $kriteria = Kriteria::orderBy('kode')->get();

        foreach ($kriteria as $i => $kriteriaI) {
            foreach ($kriteria as $j => $kriteriaJ) {
                if ($i < $j) {
                    $key = "matriks_{$kriteriaI->id}_{$kriteriaJ->id}";
                    $value = $this->data[$key] ?? 0;

                    if (!$value || $value <= 0) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    private function loadMatriksData(): void
    {
        $kriteria = Kriteria::orderBy('kode')->get();
        $matriks = MatriksAhp::all()->keyBy(function ($item) {
            return $item->kriteria_1_id . '_' . $item->kriteria_2_id;
        });

        foreach ($kriteria as $i => $kriteriaI) {
            foreach ($kriteria as $j => $kriteriaJ) {
                $key = "matriks_{$kriteriaI->id}_{$kriteriaJ->id}";

                if ($i == $j) {
                    $this->data[$key] = 1;
                } elseif ($i < $j) {
                    $matriksKey = $kriteriaI->id . '_' . $kriteriaJ->id;
                    $this->data[$key] = isset($matriks[$matriksKey]) ? (int)$matriks[$matriksKey]->nilai : 1;
                } else {
                    $matriksKey = $kriteriaJ->id . '_' . $kriteriaI->id;
                    $nilai = isset($matriks[$matriksKey]) ? $matriks[$matriksKey]->nilai : 1;
                    $this->data[$key] = $nilai != 0 ? round(1 / $nilai, 6) : 1;
                }
            }
        }
    }

    public function save(): void
    {
        $this->saveMatrix();
    }

    public function updateMatrix($i, $j, $value): void
    {
        $kriteria = Kriteria::orderBy('kode')->get();

        // Validasi index
        if (!isset($kriteria[$i]) || !isset($kriteria[$j])) {
            return;
        }

        $kriteriaI = $kriteria[$i];
        $kriteriaJ = $kriteria[$j];

        // Validasi nilai
        $value = (float) $value;
        if ($value <= 0) {
            $value = 1;
        }

        $key = "matriks_{$kriteriaI->id}_{$kriteriaJ->id}";
        $this->data[$key] = $value;

        // Update reciprocal value automatically
        if ($i != $j) {
            $reciprocalKey = "matriks_{$kriteriaJ->id}_{$kriteriaI->id}";
            $this->data[$reciprocalKey] = round(1 / $value, 6);
        }

        // Auto-save ke database langsung
        $this->autoSaveMatrix($kriteriaI, $kriteriaJ, $value);

        // Force re-render
        $this->dispatch('matrix-updated');
    }

    public function getKriteria()
    {
        return Kriteria::orderBy('kode')->get();
    }

    public function getRecommendedValue($kriteria1, $kriteria2): string
    {
        // Berikan rekomendasi berdasarkan jenis kriteria dan konteks KIP Kuliah
        $recommendations = [
            'C1_C2' => '3 (Penghasilan sedikit lebih penting dari lokasi)',
            'C1_C3' => '5 (Penghasilan cukup lebih penting dari prestasi)',
            'C1_C4' => '3 (Penghasilan sedikit lebih penting dari wawancara)',
            'C1_C5' => '3 (Penghasilan sedikit lebih penting dari nilai)',
            'C2_C3' => '2 (Lokasi sedikit lebih penting dari prestasi)',
            'C2_C4' => '1 (Lokasi sama penting dengan wawancara)',
            'C2_C5' => '2 (Lokasi sedikit lebih penting dari nilai)',
            'C3_C4' => '1 (Prestasi sama penting dengan wawancara)',
            'C3_C5' => '1 (Prestasi sama penting dengan nilai)',
            'C4_C5' => '1 (Wawancara sama penting dengan nilai)',
        ];

        $key = $kriteria1->kode . '_' . $kriteria2->kode;
        return $recommendations[$key] ?? '1 (sama penting)';
    }

    public function resetMatrix(): void
    {
        $kriteria = Kriteria::orderBy('kode')->get();

        foreach ($kriteria as $i => $kriteriaI) {
            foreach ($kriteria as $j => $kriteriaJ) {
                $key = "matriks_{$kriteriaI->id}_{$kriteriaJ->id}";
                $this->data[$key] = 1;

                // Auto-save juga
                $this->autoSaveMatrix($kriteriaI, $kriteriaJ, 1);
            }
        }

        // Reset all calculation results in memory
        $this->bobotResults = [];
        $this->consistencyRatio = null;
        $this->matrixNormalisasi = null;
        $this->weightedSum = null;
        $this->lambdaMax = null;
        $this->consistencyIndex = null;
        $this->randomIndex = null;

        // IMPORTANT: Reset all calculation results in database too
        // Delete all AHP calculation records to make reset persistent
        \App\Models\PerhitunganAhp::truncate();

        // Reset weights in kriteria table
        Kriteria::query()->update(['bobot' => null]);

        // Also clear SAW results since they depend on AHP weights
        HasilSeleksi::truncate();

        // Refresh the criteria table display
        $this->refreshKriteriaTable();

        Notification::make()
            ->title('Matrix Direset')
            ->body('Semua nilai matrix telah direset ke nilai default (1) dan hasil perhitungan dihapus.')
            ->success()
            ->send();
    }

    public function resetMatrixAction(): Action
    {
        return Action::make('resetMatrix')
            ->label('Reset Matrix')
            ->icon('heroicon-o-arrow-path')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Reset Matrix Perbandingan')
            ->modalDescription('Apakah Anda yakin ingin mereset semua nilai matrix ke default (1)? Semua perubahan yang belum disimpan akan hilang!')
            ->modalSubmitActionLabel('Ya, Reset Matrix')
            ->modalCancelActionLabel('Batal')
            ->action(fn() => $this->resetMatrix());
    }

    private function hasUnsavedChanges(): bool
    {
        $kriteria = Kriteria::orderBy('kode')->get();
        $matriks = MatriksAhp::all()->keyBy(function ($item) {
            return $item->kriteria_1_id . '_' . $item->kriteria_2_id;
        });

        foreach ($kriteria as $i => $kriteriaI) {
            foreach ($kriteria as $j => $kriteriaJ) {
                if ($i < $j) { // Hanya cek setengah matriks (upper triangle)
                    $key = "matriks_{$kriteriaI->id}_{$kriteriaJ->id}";
                    $currentValue = (float) ($this->data[$key] ?? 1);

                    $matriksKey = $kriteriaI->id . '_' . $kriteriaJ->id;
                    $savedValue = isset($matriks[$matriksKey]) ? (float)$matriks[$matriksKey]->nilai : 1;

                    // Jika ada perbedaan nilai, berarti ada perubahan yang belum disimpan
                    if (abs($currentValue - $savedValue) > 0.000001) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    private function autoSaveMatrix($kriteriaI, $kriteriaJ, $value): void
    {
        try {
            // Simpan nilai utama
            MatriksAhp::updateOrCreate(
                [
                    'kriteria_1_id' => $kriteriaI->id,
                    'kriteria_2_id' => $kriteriaJ->id,
                ],
                ['nilai' => $value]
            );

            // Simpan kebalikannya juga (kalau bukan diagonal)
            if ($kriteriaI->id != $kriteriaJ->id) {
                MatriksAhp::updateOrCreate(
                    [
                        'kriteria_1_id' => $kriteriaJ->id,
                        'kriteria_2_id' => $kriteriaI->id,
                    ],
                    ['nilai' => round(1 / $value, 6)]
                );
            }
        } catch (\Exception $e) {
            // Silent fail, biar ga ganggu UX
            logger()->error('Auto-save matrix gagal: ' . $e->getMessage());
        }
    }
}
