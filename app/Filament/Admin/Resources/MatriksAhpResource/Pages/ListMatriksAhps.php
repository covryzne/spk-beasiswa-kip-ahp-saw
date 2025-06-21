<?php

namespace App\Filament\Admin\Resources\MatriksAhpResource\Pages;

use App\Filament\Admin\Resources\MatriksAhpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMatriksAhps extends ListRecords
{
    protected static string $resource = MatriksAhpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
