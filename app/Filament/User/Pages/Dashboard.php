<?php

namespace App\Filament\User\Pages;

use App\Models\HasilSeleksi;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Actions;
use Filament\Notifications\Notification;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = 0;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('refresh')
                ->label('Refresh Data')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function () {
                    $this->dispatch('$refresh');
                    Notification::make()
                        ->title('Data Diperbarui')
                        ->body('Dashboard telah diperbarui dengan data terbaru.')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\User\Widgets\HasilSeleksiStats::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'md' => 2,
            'lg' => 3,
            'xl' => 3,
            '2xl' => 4,
        ];
    }
}
