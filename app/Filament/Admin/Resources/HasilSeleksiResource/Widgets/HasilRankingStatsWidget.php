<?php

namespace App\Filament\Admin\Resources\HasilSeleksiResource\Widgets;

use App\Models\HasilSeleksi;
use App\Models\CalonMahasiswa;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HasilRankingStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPendaftar = CalonMahasiswa::count();
        $totalDiterima = HasilSeleksi::where('status', 'diterima')->count();
        $totalDitolak = HasilSeleksi::where('status', 'ditolak')->count();

        // Cut-off score (skor minimum yang diterima)
        $cutOffScore = HasilSeleksi::where('status', 'diterima')
            ->min('skor');

        // Tingkat kompetisi (persentase diterima)
        $tingkatKompetisi = $totalPendaftar > 0
            ? round(($totalDiterima / $totalPendaftar) * 100, 1)
            : 0;

        return [
            Stat::make('Kuota Beasiswa', $totalDiterima)
                ->description('Mahasiswa diterima dari ' . $totalPendaftar . ' pendaftar')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->extraAttributes([
                    'class' => 'bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20',
                ]),

            Stat::make('Cut-off Score', $cutOffScore ? number_format($cutOffScore, 4) : '-')
                ->description('Skor minimum untuk diterima')
                ->descriptionIcon('heroicon-m-chart-bar-square')
                ->color('warning')
                ->chart([3, 8, 5, 12, 9, 16, 11])
                ->extraAttributes([
                    'class' => 'bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-amber-900/20 dark:to-yellow-900/20',
                ]),

            Stat::make('Tingkat Kompetisi', $tingkatKompetisi . '%')
                ->description('Persentase penerimaan beasiswa')
                ->descriptionIcon('heroicon-m-fire')
                ->color($tingkatKompetisi <= 30 ? 'danger' : ($tingkatKompetisi <= 60 ? 'warning' : 'success'))
                ->chart([12, 6, 14, 8, 18, 10, 20])
                ->extraAttributes([
                    'class' => 'bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20',
                ]),
        ];
    }
}
