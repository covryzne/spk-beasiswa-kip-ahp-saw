<?php

namespace App\Filament\Admin\Resources\HasilSeleksiResource\Pages;

use App\Filament\Admin\Resources\HasilSeleksiResource;
use App\Models\HasilSeleksi;
use App\Models\CalonMahasiswa;
use App\Services\SawService;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ListHasilSeleksis extends ListRecords
{
    protected static string $resource = HasilSeleksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('ubahKuota')
                ->label('Ubah Kuota Beasiswa')
                ->icon('heroicon-o-adjustments-horizontal')
                ->color('warning')
                ->form(function () {
                    $totalPendaftar = CalonMahasiswa::count();
                    $currentKuota = HasilSeleksi::where('status', 'diterima')->count();

                    return [
                        TextInput::make('kuota')
                            ->label('Kuota Beasiswa')
                            ->numeric()
                            ->required()
                            ->default($currentKuota)
                            ->minValue(1)
                            ->maxValue($totalPendaftar)
                            ->helperText("Jumlah mahasiswa yang akan diterima beasiswa (Max: {$totalPendaftar} pendaftar)")
                            ->rules([
                                function () use ($totalPendaftar) {
                                    return function (string $attribute, $value, \Closure $fail) use ($totalPendaftar) {
                                        if ((int) $value > $totalPendaftar) {
                                            $fail("❌ Kuota beasiswa ({$value}) tidak boleh melebihi total pendaftar ({$totalPendaftar} mahasiswa).");
                                        }

                                        if ((int) $value < 1) {
                                            $fail("❌ Kuota beasiswa minimal adalah 1 mahasiswa.");
                                        }
                                    };
                                }
                            ])
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set, $livewire) use ($totalPendaftar) {
                                if ((int) $state > $totalPendaftar) {
                                    // Reset to max allowed value
                                    $set('kuota', $totalPendaftar);

                                    // Send notification
                                    Notification::make()
                                        ->title('Kuota Melebihi Batas!')
                                        ->body("Kuota tidak boleh melebihi total pendaftar ({$totalPendaftar} mahasiswa). Kuota otomatis disesuaikan.")
                                        ->warning()
                                        ->duration(5000)
                                        ->send();
                                }
                            })
                            ->suffix('mahasiswa')
                            ->placeholder('Masukkan jumlah kuota...')
                    ];
                })
                ->requiresConfirmation()
                ->modalHeading('Ubah Kuota Beasiswa')
                ->modalDescription(function () {
                    $totalPendaftar = CalonMahasiswa::count();
                    $currentKuota = HasilSeleksi::where('status', 'diterima')->count();
                    $totalHasilSeleksi = HasilSeleksi::count();

                    return "Mengubah kuota akan memperbarui status penerimaan semua mahasiswa berdasarkan ranking mereka.";
                })
                ->modalSubmitActionLabel('Update Kuota')
                ->action(function (array $data) {
                    $totalPendaftar = CalonMahasiswa::count();
                    $kuotaBaru = (int) $data['kuota'];

                    // Double check validation with better messages
                    if ($kuotaBaru > $totalPendaftar) {
                        Notification::make()
                            ->title('❌ Kuota Melebihi Pendaftar!')
                            ->body("Kuota beasiswa ({$kuotaBaru}) tidak boleh melebihi total pendaftar ({$totalPendaftar} mahasiswa). Silakan masukkan kuota yang sesuai.")
                            ->danger()
                            ->duration(8000)
                            ->send();
                        return;
                    }

                    if ($kuotaBaru < 1) {
                        Notification::make()
                            ->title('❌ Kuota Tidak Valid!')
                            ->body("Kuota beasiswa minimal adalah 1 mahasiswa.")
                            ->danger()
                            ->duration(5000)
                            ->send();
                        return;
                    }

                    $currentKuota = HasilSeleksi::where('status', 'diterima')->count();

                    try {
                        $sawService = app(\App\Services\SawService::class);
                        $sawService->updateStatusByKuota($kuotaBaru);

                        // Enhanced notification with details
                        $statusChange = '';
                        if ($kuotaBaru > $currentKuota) {
                            $diff = $kuotaBaru - $currentKuota;
                            $statusChange = " (+{$diff} mahasiswa tambahan diterima)";
                        } elseif ($kuotaBaru < $currentKuota) {
                            $diff = $currentKuota - $kuotaBaru;
                            $statusChange = " (-{$diff} mahasiswa status berubah jadi ditolak)";
                        } else {
                            $statusChange = " (tidak ada perubahan status)";
                        }

                        // Get final stats
                        $finalDiterima = HasilSeleksi::where('status', 'diterima')->count();
                        $finalDitolak = HasilSeleksi::where('status', 'ditolak')->count();

                        Notification::make()
                            ->title('✅ Kuota Berhasil Diperbarui!')
                            ->body("Kuota beasiswa diupdate dari {$currentKuota} menjadi {$kuotaBaru} mahasiswa{$statusChange}\n\nStatus Terkini:\n• Diterima: {$finalDiterima} mahasiswa\n• Ditolak: {$finalDitolak} mahasiswa")
                            ->success()
                            ->duration(10000)
                            ->send();

                        // Refresh the page to show updated data
                        return redirect()->to(static::getUrl());
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('❌ Gagal Memperbarui Kuota!')
                            ->body('Terjadi kesalahan saat memperbarui kuota: ' . $e->getMessage())
                            ->danger()
                            ->duration(10000)
                            ->send();
                    }
                }),
        ];
    }

    // Add stats widgets
    protected function getHeaderWidgets(): array
    {
        return [
            HasilSeleksiResource\Widgets\HasilRankingStatsWidget::class,
        ];
    }
}
