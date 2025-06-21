<?php

namespace App\Filament\User\Resources\CalonMahasiswaResource\Pages;

use App\Filament\User\Resources\CalonMahasiswaResource;
use Filament\Resources\Pages\ListRecords;

class ListCalonMahasiswa extends ListRecords
{
    protected static string $resource = CalonMahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tidak ada actions untuk user
        ];
    }
}
