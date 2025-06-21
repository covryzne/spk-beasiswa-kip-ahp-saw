<?php

namespace App\Filament\User\Resources\HasilSeleksiResource\Pages;

use App\Filament\User\Resources\HasilSeleksiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHasilSeleksi extends EditRecord
{
    protected static string $resource = HasilSeleksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
