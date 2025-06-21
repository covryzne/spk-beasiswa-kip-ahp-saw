<?php

namespace App\Filament\Admin\Resources\CalonMahasiswaResource\Pages;

use App\Filament\Admin\Resources\CalonMahasiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCalonMahasiswas extends ListRecords
{
    protected static string $resource = CalonMahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
