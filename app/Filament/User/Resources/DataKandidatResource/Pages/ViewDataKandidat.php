<?php

namespace App\Filament\User\Resources\DataKandidatResource\Pages;

use App\Filament\User\Resources\DataKandidatResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;

class ViewDataKandidat extends ViewRecord
{
    protected static string $resource = DataKandidatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No edit/delete actions for user panel
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
