<?php

namespace App\Filament\User\Widgets;

use App\Models\HasilSeleksi;
use Filament\Widgets\ChartWidget;

class ScoreDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Skor Kandidat';

    protected static ?int $sort = 4;

    protected static ?string $description = 'Sebaran skor SAW seluruh kandidat';

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Ambil data skor untuk membuat histogram
        $scores = HasilSeleksi::pluck('skor')->toArray();

        if (empty($scores)) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        // Buat range untuk histogram
        $minScore = min($scores);
        $maxScore = max($scores);
        $ranges = [];
        $counts = [];

        // Bagi menjadi 8 range
        $step = ($maxScore - $minScore) / 8;

        for ($i = 0; $i < 8; $i++) {
            $rangeStart = $minScore + ($i * $step);
            $rangeEnd = $minScore + (($i + 1) * $step);

            $count = count(array_filter($scores, function ($score) use ($rangeStart, $rangeEnd, $i) {
                return $i == 7 ? ($score >= $rangeStart && $score <= $rangeEnd) : ($score >= $rangeStart && $score < $rangeEnd);
            }));

            $ranges[] = number_format($rangeStart, 3) . '-' . number_format($rangeEnd, 3);
            $counts[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kandidat',
                    'data' => $counts,
                    'backgroundColor' => [
                        'rgba(239, 68, 68, 0.6)',   // Red
                        'rgba(245, 158, 11, 0.6)',  // Orange
                        'rgba(251, 191, 36, 0.6)',  // Yellow
                        'rgba(163, 230, 53, 0.6)',  // Lime
                        'rgba(34, 197, 94, 0.6)',   // Green
                        'rgba(6, 182, 212, 0.6)',   // Cyan
                        'rgba(59, 130, 246, 0.6)',  // Blue
                        'rgba(147, 51, 234, 0.6)',  // Purple
                    ],
                    'borderColor' => [
                        'rgba(239, 68, 68, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(251, 191, 36, 1)',
                        'rgba(163, 230, 53, 1)',
                        'rgba(34, 197, 94, 1)',
                        'rgba(6, 182, 212, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(147, 51, 234, 1)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $ranges,
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
                    'text' => 'Distribusi Skor SAW',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'title' => 'function(context) { return "Range Skor: " + context[0].label; }',
                        'label' => 'function(context) { return context.dataset.label + ": " + context.formattedValue + " kandidat"; }',
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Kandidat',
                    ],
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Range Skor SAW',
                    ],
                ],
            ],
        ];
    }
}
