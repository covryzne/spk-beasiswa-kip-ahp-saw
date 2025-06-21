<?php

namespace App\Filament\User\Resources\CalonMahasiswaResource\Pages;

use App\Filament\User\Resources\CalonMahasiswaResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;

class ViewCalonMahasiswa extends ViewRecord
{
    protected static string $resource = CalonMahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tidak ada actions untuk user
        ];
    }

    /**
     * Override infolist untuk memastikan menggunakan infolist dari resource
     */
    public function infolist(Infolist $infolist): Infolist
    {
        return static::getResource()::infolist($infolist);
    }
}
