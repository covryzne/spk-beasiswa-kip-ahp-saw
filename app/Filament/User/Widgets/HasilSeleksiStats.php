<?php

namespace App\Filament\User\Widgets;

use App\Models\HasilSeleksi;
use App\Models\CalonMahasiswa;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HasilSeleksiStats extends BaseStatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalHasil = HasilSeleksi::count();
        $totalCalon = CalonMahasiswa::count();
        $avgSkor = HasilSeleksi::avg('skor') ?: 0;
        $maxSkor = HasilSeleksi::max('skor') ?: 0;

        return [
            Stat::make('Total Hasil Seleksi', $totalHasil)
                ->description('Kandidat yang telah dievaluasi')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary')
                ->chart([7, 12, 18, 15, 22, 17, $totalHasil]),

            Stat::make('Rata-rata Skor', number_format($avgSkor, 4))
                ->description('Skor SAW rata-rata')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('success')
                ->chart([0.2, 0.4, 0.6, 0.8, 0.7, 0.9, $avgSkor]),

            Stat::make('Skor Tertinggi', number_format($maxSkor, 4))
                ->description('Skor SAW terbaik')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('warning')
                ->chart([0.1, 0.3, 0.5, 0.7, 0.8, 0.9, $maxSkor]),
        ];
    }
}
