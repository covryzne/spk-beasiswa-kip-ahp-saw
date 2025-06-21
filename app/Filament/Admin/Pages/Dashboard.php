<?php

namespace App\Filament\Admin\Pages;

use App\Models\CalonMahasiswa;
use App\Models\HasilSeleksi;
use App\Models\Kriteria;
use App\Models\PerhitunganAhp;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = 0;

    public function getWidgets(): array
    {
        return [
            \App\Filament\Admin\Widgets\SystemStatsWidget::class,
            \App\Filament\Admin\Widgets\HasilSeleksiChart::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 4,
        ];
    }
}
