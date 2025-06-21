<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\HasilSeleksiResource\Pages;
use App\Filament\User\Resources\HasilSeleksiResource\RelationManagers;
use App\Models\HasilSeleksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasilSeleksiResource extends Resource
{
    protected static ?string $model = HasilSeleksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationLabel = 'Hasil Seleksi (Backup)';

    protected static ?string $modelLabel = 'Hasil Seleksi';

    protected static ?string $pluralModelLabel = 'Hasil Seleksi';

    // Hide from navigation - keep for backup
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHasilSeleksis::route('/'),
            'create' => Pages\CreateHasilSeleksi::route('/create'),
            'edit' => Pages\EditHasilSeleksi::route('/{record}/edit'),
        ];
    }
}
