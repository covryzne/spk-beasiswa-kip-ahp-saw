<?php

namespace App\Filament\Admin\Widgets;

use App\Models\HasilSeleksi;
use Filament\Widgets\ChartWidget;

class HasilSeleksiChart extends ChartWidget
{
    protected static ?string $heading = 'Top 10 Ranking Calon Mahasiswa';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

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
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $data->pluck('nama')->map(function ($nama) {
                return strlen($nama) > 12 ? substr($nama, 0, 12) . '...' : $nama;
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
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
                    'text' => 'Distribusi Skor 10 Kandidat Terbaik',
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
