<?php

namespace App\Filament\User\Resources\HasilRankingResource\Pages;

use App\Filament\User\Resources\HasilRankingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHasilRankings extends ListRecords
{
    protected static string $resource = HasilRankingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
