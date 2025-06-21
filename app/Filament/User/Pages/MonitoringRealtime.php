<?php

namespace App\Filament\User\Pages;

use App\Models\CalonMahasiswa;
use App\Models\HasilSeleksi;
use Filament\Pages\Page;

class MonitoringRealtime extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-signal';

    protected static ?string $navigationLabel = 'Monitoring Real-time (Backup)';

    protected static ?string $title = 'Monitoring Real-time';

    protected static string $view = 'filament.user.pages.monitoring-realtime';

    protected static ?string $navigationGroup = 'Monitoring';

    // Hide from navigation - keep for backup
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 2;

    public $stats = [];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->stats = [
            'total_kandidat' => CalonMahasiswa::count(),
            'sudah_dievaluasi' => HasilSeleksi::count(),
            'persentase_selesai' => CalonMahasiswa::count() > 0
                ? round((HasilSeleksi::count() / CalonMahasiswa::count()) * 100, 1)
                : 0,
            'skor_tertinggi' => HasilSeleksi::max('skor') ?: 0,
            'skor_terendah' => HasilSeleksi::min('skor') ?: 0,
            'rata_rata' => HasilSeleksi::avg('skor') ?: 0,
            'last_update' => now()->format('d M Y H:i:s'),
        ];
    }

    public function refreshData()
    {
        $this->loadStats();
        $this->dispatch('stats-updated', $this->stats);
    }
}
