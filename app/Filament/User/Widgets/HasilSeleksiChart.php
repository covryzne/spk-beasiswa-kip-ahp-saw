<?php

namespace App\Filament\User\Widgets;

use App\Models\HasilSeleksi;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class HasilSeleksiChart extends ChartWidget
{
    protected static ?string $heading = 'Top 10 Kandidat Terbaik';

    protected static ?int $sort = 3;

    protected static ?string $description = 'Ranking kandidat berdasarkan skor SAW';

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = HasilSeleksi::join('calon_mahasiswa', 'hasil_seleksi.calon_mahasiswa_id', '=', 'calon_mahasiswa.id')
            ->orderBy('hasil_seleksi.skor', 'desc')
            ->limit(10)
            ->get(['calon_mahasiswa.nama', 'hasil_seleksi.skor']);

        return [
            'datasets' => [
                [
                    'label' => 'Skor SAW',
                    'data' => $data->pluck('skor')->toArray(),
                    'backgroundColor' => [
                        '#3B82F6',
                        '#10B981',
                        '#F59E0B',
                        '#EF4444',
                        '#8B5CF6',
                        '#06B6D4',
                        '#84CC16',
                        '#F97316',
                        '#EC4899',
                        '#6366F1'
                    ],
                    'borderColor' => [
                        '#1D4ED8',
                        '#059669',
                        '#D97706',
                        '#DC2626',
                        '#7C3AED',
                        '#0891B2',
                        '#65A30D',
                        '#EA580C',
                        '#DB2777',
                        '#4F46E5'
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $data->pluck('nama')->map(function ($nama) {
                return strlen($nama) > 15 ? substr($nama, 0, 15) . '...' : $nama;
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'title' => [
                    'display' => true,
                    'text' => '10 Kandidat dengan Skor Tertinggi',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Skor SAW',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Nama Kandidat',
                    ],
                ],
            ],
        ];
    }
}
