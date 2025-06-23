<?php

namespace App\Filament\Admin\Resources\DataMahasiswaResource\Pages;

use App\Filament\Admin\Resources\DataMahasiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDataMahasiswas extends ListRecords
{
    protected static string $resource = DataMahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
