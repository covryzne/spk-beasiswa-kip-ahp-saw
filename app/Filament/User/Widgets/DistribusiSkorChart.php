<?php

namespace App\Filament\User\Widgets;

use App\Models\HasilSeleksi;
use Filament\Widgets\ChartWidget;

class DistribusiSkorChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Skor Kandidat';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        // Buat range skor (0.0-0.2, 0.2-0.4, 0.4-0.6, 0.6-0.8, 0.8-1.0)
        $ranges = [
            '0.0-0.2' => HasilSeleksi::whereBetween('skor', [0.0, 0.2])->count(),
            '0.2-0.4' => HasilSeleksi::whereBetween('skor', [0.2, 0.4])->count(),
            '0.4-0.6' => HasilSeleksi::whereBetween('skor', [0.4, 0.6])->count(),
            '0.6-0.8' => HasilSeleksi::whereBetween('skor', [0.6, 0.8])->count(),
            '0.8-1.0' => HasilSeleksi::whereBetween('skor', [0.8, 1.0])->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kandidat',
                    'data' => array_values($ranges),
                    'backgroundColor' => [
                        '#EF4444', // Red
                        '#F97316', // Orange
                        '#F59E0B', // Amber
                        '#10B981', // Emerald
                        '#059669', // Green
                    ],
                    'borderColor' => [
                        '#DC2626',
                        '#EA580C',
                        '#D97706',
                        '#059669',
                        '#047857',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => array_keys($ranges),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'right',
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Sebaran Skor SAW',
                ],
            ],
        ];
    }
}
