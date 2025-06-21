<?php

namespace App\Filament\Admin\Resources\MatriksAhpResource\Pages;

use App\Filament\Admin\Resources\MatriksAhpResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMatriksAhp extends EditRecord
{
    protected static string $resource = MatriksAhpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
