<?php

namespace App\Filament\Admin\Widgets;

use App\Models\CalonMahasiswa;
use App\Models\HasilSeleksi;
use App\Models\Kriteria;
use App\Models\PerhitunganAhp;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SystemStatsWidget extends BaseStatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalKriteria = Kriteria::count();
        $totalCalon = CalonMahasiswa::count();
        $totalHasil = HasilSeleksi::count();
        $totalPerhitungan = PerhitunganAhp::count();

        return [
            Stat::make('Total Kriteria', $totalKriteria)
                ->description('Kriteria seleksi yang tersedia')
                ->descriptionIcon('heroicon-m-list-bullet')
                ->color($totalKriteria > 0 ? 'success' : 'danger')
                ->chart([1, 3, 5, 7, $totalKriteria]),

            Stat::make('Calon Mahasiswa', $totalCalon)
                ->description('Total calon mahasiswa yang mendaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color($totalCalon > 0 ? 'info' : 'warning')
                ->chart([2, 5, 8, 12, $totalCalon]),

            Stat::make('Hasil Seleksi', $totalHasil)
                ->description('Hasil perhitungan yang telah selesai')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($totalHasil > 0 ? 'primary' : 'warning')
                ->chart([1, 4, 7, 9, $totalHasil]),

            Stat::make('Perhitungan AHP', $totalPerhitungan)
                ->description('Bobot kriteria yang telah dihitung')
                ->descriptionIcon('heroicon-m-calculator')
                ->color($totalPerhitungan > 0 ? 'success' : 'warning')
                ->chart([0, 1, 2, 3, $totalPerhitungan]),
        ];
    }
}
