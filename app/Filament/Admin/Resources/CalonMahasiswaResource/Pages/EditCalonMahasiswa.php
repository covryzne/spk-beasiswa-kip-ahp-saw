<?php

namespace App\Filament\Admin\Resources\CalonMahasiswaResource\Pages;

use App\Filament\Admin\Resources\CalonMahasiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCalonMahasiswa extends EditRecord
{
    protected static string $resource = CalonMahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
