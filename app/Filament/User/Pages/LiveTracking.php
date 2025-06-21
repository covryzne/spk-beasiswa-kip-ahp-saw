<?php

namespace App\Filament\User\Pages;

use App\Models\HasilSeleksi;
use App\Models\CalonMahasiswa;
use Filament\Pages\Page;

class LiveTracking extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-eye';

    protected static ?string $navigationLabel = 'Live Tracking (Backup)';

    protected static ?string $title = 'Live Tracking Seleksi';

    protected static string $view = 'filament.user.pages.live-tracking';

    protected static ?string $navigationGroup = 'Monitoring';

    // Hide from navigation - keep for backup
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 3;

    public $topCandidates = [];
    public $recentUpdates = [];
    public $systemActivity = [];

    public function mount()
    {
        $this->loadLiveData();
    }

    public function loadLiveData()
    {
        // Top 5 kandidat
        $this->topCandidates = HasilSeleksi::join('calon_mahasiswa', 'hasil_seleksi.calon_mahasiswa_id', '=', 'calon_mahasiswa.id')
            ->orderBy('hasil_seleksi.rank', 'asc')
            ->limit(5)
            ->get(['hasil_seleksi.*', 'calon_mahasiswa.nama'])
            ->toArray();

        // Recent updates (simulasi)
        $this->recentUpdates = [
            [
                'time' => now()->subMinutes(2)->format('H:i:s'),
                'message' => 'Evaluasi kandidat baru selesai',
                'type' => 'success',
                'count' => 3
            ],
            [
                'time' => now()->subMinutes(5)->format('H:i:s'),
                'message' => 'Ranking diperbarui',
                'type' => 'info',
                'count' => 1
            ],
            [
                'time' => now()->subMinutes(8)->format('H:i:s'),
                'message' => 'Data kandidat baru ditambahkan',
                'type' => 'warning',
                'count' => 2
            ],
        ];

        // System activity
        $this->systemActivity = [
            'total_sessions' => rand(15, 35),
            'active_evaluators' => rand(2, 8),
            'pending_evaluations' => CalonMahasiswa::count() - HasilSeleksi::count(),
            'system_load' => rand(15, 45),
        ];
    }

    public function refreshLiveData()
    {
        $this->loadLiveData();
        $this->dispatch('live-data-updated');
    }
}
