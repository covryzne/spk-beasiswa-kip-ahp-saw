<?php

namespace App\Filament\User\Widgets;

use App\Models\CalonMahasiswa;
use App\Models\HasilSeleksi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProgressStatistics extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalKandidat = CalonMahasiswa::count();
        $sudahDievaluasi = HasilSeleksi::count();
        $persentaseSelesai = $totalKandidat > 0 ? round(($sudahDievaluasi / $totalKandidat) * 100, 1) : 0;
        $avgSkor = HasilSeleksi::avg('skor') ?: 0;
        $maxSkor = HasilSeleksi::max('skor') ?: 0;

        // Hitung quota yang lolos (misal 40% dari total kandidat)
        $quotaLolos = ceil($totalKandidat * 0.4);

        return [
            Stat::make('Progress Evaluasi', $persentaseSelesai . '%')
                ->description("$sudahDievaluasi dari $totalKandidat kandidat")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color($persentaseSelesai >= 100 ? 'success' : ($persentaseSelesai >= 50 ? 'warning' : 'danger')),

            Stat::make('Rata-rata Skor', number_format($avgSkor, 4))
                ->description('Skor SAW seluruh kandidat')
                ->descriptionIcon('heroicon-m-calculator')
                ->chart([3, 7, 4, 8, 6, 9, 5])
                ->color('info'),

            Stat::make('Quota Lolos', $quotaLolos . ' kandidat')
                ->description('40% dari total kandidat')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->chart([1, 3, 2, 4, 3, 5, 4])
                ->color('success'),
        ];
    }
}
