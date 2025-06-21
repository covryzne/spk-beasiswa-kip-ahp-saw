<?php

namespace App\Filament\User\Widgets;

use App\Models\HasilSeleksi;
use Filament\Widgets\Widget;
use Carbon\Carbon;

class NotificationWidget extends Widget
{
    protected static string $view = 'filament.user.widgets.notification-widget';

    protected static ?int $sort = 0;

    protected function getViewData(): array
    {
        $recentUpdates = HasilSeleksi::with('calonMahasiswa')
            ->where('updated_at', '>=', Carbon::now()->subDays(7))
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $latestUpdate = HasilSeleksi::orderBy('updated_at', 'desc')->first();

        return [
            'recentUpdates' => $recentUpdates,
            'latestUpdate' => $latestUpdate,
            'hasUpdates' => $recentUpdates->count() > 0,
        ];
    }
}
