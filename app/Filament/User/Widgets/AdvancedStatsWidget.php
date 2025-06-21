<?php

namespace App\Filament\User\Widgets;

use App\Models\HasilSeleksi;
use App\Models\CalonMahasiswa;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdvancedStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalHasil = HasilSeleksi::count();
        $totalCalonMahasiswa = CalonMahasiswa::count();
        $avgSkor = HasilSeleksi::avg('skor') ?: 0;
        $maxSkor = HasilSeleksi::max('skor') ?: 0;
        $minSkor = HasilSeleksi::min('skor') ?: 0;

        // Hitung persentase yang sudah dievaluasi
        $persentaseEvaluasi = $totalCalonMahasiswa > 0 ? ($totalHasil / $totalCalonMahasiswa) * 100 : 0;

        // Hitung kandidat dengan skor tinggi (>= 0.7)
        $skorTinggi = HasilSeleksi::where('skor', '>=', 0.7)->count();

        return [
            Stat::make('Total Kandidat', $totalCalonMahasiswa)
                ->description('Calon mahasiswa terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Sudah Dievaluasi', $totalHasil)
                ->description(number_format($persentaseEvaluasi, 1) . '% dari total')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Rata-rata Skor', number_format($avgSkor, 4))
                ->description('Skor SAW rata-rata')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),

            Stat::make('Skor Tertinggi', number_format($maxSkor, 4))
                ->description('Best performer')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('success'),

            Stat::make('Kandidat Potensial', $skorTinggi)
                ->description('Skor >= 0.7000')
                ->descriptionIcon('heroicon-m-star')
                ->color('info'),

            Stat::make('Range Skor', number_format($maxSkor - $minSkor, 4))
                ->description('Selisih tertinggi-terendah')
                ->descriptionIcon('heroicon-m-arrows-up-down')
                ->color('gray'),
        ];
    }
}
