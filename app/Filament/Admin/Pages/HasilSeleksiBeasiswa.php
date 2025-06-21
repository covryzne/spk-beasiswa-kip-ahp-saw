<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use App\Models\HasilSeleksi;
use App\Models\CalonMahasiswa;

class HasilSeleksiBeasiswa extends Page implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;    // Hide this page since we're using HasilSeleksiResource now
    protected static bool $shouldRegisterNavigation = false;

    public $hasilSeleksi = [];
    public $kuotaBeasiswa = 5;
    public $cutOffScore = null;

    public function mount(): void
    {
        $this->loadHasilSeleksi();
        $this->form->fill([
            'kuota_beasiswa' => $this->kuotaBeasiswa
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Pengaturan Kuota')
                    ->description('Tentukan berapa mahasiswa yang akan diterima beasiswa KIP')
                    ->schema([
                        TextInput::make('kuota_beasiswa')
                            ->label('Jumlah Penerima Beasiswa')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->default(5)
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state) {
                                $this->kuotaBeasiswa = (int) $state;
                                $this->calculateCutOffScore();
                            })
                            ->helperText('Masukkan jumlah mahasiswa yang akan diterima beasiswa')
                            ->suffix('mahasiswa')
                    ])
            ])
            ->statePath('data');
    }

    private function loadHasilSeleksi(): void
    {
        $this->hasilSeleksi = HasilSeleksi::with('calonMahasiswa')
            ->orderBy('skor', 'desc')
            ->get()
            ->toArray();

        $this->calculateCutOffScore();
    }

    private function calculateCutOffScore(): void
    {
        $totalCandidates = count($this->hasilSeleksi);

        if ($totalCandidates > 0 && $this->kuotaBeasiswa > 0) {
            if ($this->kuotaBeasiswa <= $totalCandidates) {
                // Normal case: ambil skor kandidat terakhir yang lolos
                $this->cutOffScore = round($this->hasilSeleksi[$this->kuotaBeasiswa - 1]['skor'], 4);
            } else {
                // Case: kuota lebih besar dari total kandidat (semua lolos)
                $this->cutOffScore = round($this->hasilSeleksi[$totalCandidates - 1]['skor'], 4);
            }
        } else {
            $this->cutOffScore = '-';
        }
    }

    public function updateKuota(): void
    {
        $data = $this->form->getState();
        $this->kuotaBeasiswa = (int) $data['kuota_beasiswa'];
        $this->calculateCutOffScore();

        Notification::make()
            ->title('Kuota Beasiswa Diperbarui')
            ->body("Kuota beasiswa berhasil diubah menjadi {$this->kuotaBeasiswa} mahasiswa")
            ->success()
            ->send();
    }

    public function refreshData(): void
    {
        $this->loadHasilSeleksi();

        Notification::make()
            ->title('Data Diperbarui')
            ->body('Hasil seleksi telah diperbarui dari database')
            ->success()
            ->send();
    }
    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('setKuota')
                ->label('Atur Kuota Beasiswa')
                ->icon('heroicon-o-adjustments-horizontal')
                ->color('primary')
                ->form([
                    \Filament\Forms\Components\TextInput::make('kuota')
                        ->label('Jumlah Penerima Beasiswa')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(100)
                        ->default(fn() => $this->kuotaBeasiswa)
                        ->required()
                        ->helperText('Masukkan jumlah mahasiswa yang akan diterima beasiswa')
                        ->suffix('mahasiswa')
                ])
                ->modalHeading('Pengaturan Kuota Beasiswa')
                ->modalDescription('Tentukan berapa mahasiswa yang akan diterima beasiswa KIP kuliah.')
                ->modalSubmitActionLabel('Simpan Kuota')
                ->action(function (array $data): void {
                    $this->kuotaBeasiswa = (int) $data['kuota'];
                    $this->calculateCutOffScore();

                    \Filament\Notifications\Notification::make()
                        ->title('Kuota Beasiswa Diperbarui')
                        ->body("Kuota diubah menjadi {$this->kuotaBeasiswa} mahasiswa")
                        ->success()
                        ->send();
                }),
        ];
    }
}
