<?php

namespace App\Filament\User\Resources\DataKandidatResource\Pages;

use App\Filament\User\Resources\DataKandidatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDataKandidats extends ListRecords
{
    protected static string $resource = DataKandidatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
