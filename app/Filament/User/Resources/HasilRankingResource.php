<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\HasilRankingResource\Pages;
use App\Filament\User\Resources\HasilRankingResource\RelationManagers;
use App\Models\HasilSeleksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasilRankingResource extends Resource
{
    protected static ?string $model = HasilSeleksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationLabel = 'Hasil Ranking';

    protected static ?string $modelLabel = 'Hasil Ranking';

    protected static ?string $pluralModelLabel = 'Hasil Ranking';

    protected static ?string $navigationGroup = 'Perankingan';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form disabled for user panel - read only
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('calonMahasiswa');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ranking')
                    ->label('Rank')
                    ->sortable()
                    ->badge()
                    ->color(fn($state) => match (true) {
                        $state == 1 => 'warning',
                        $state <= 3 => 'success',
                        $state <= 10 => 'info',
                        default => 'gray'
                    })
                    ->icon(fn($state) => match (true) {
                        $state == 1 => 'heroicon-o-trophy',
                        $state <= 3 => 'heroicon-o-star',
                        default => null
                    }),

                TextColumn::make('calonMahasiswa.nama')
                    ->label('Nama Calon Mahasiswa')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('calonMahasiswa.kode')
                    ->label('Kode')
                    ->searchable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('skor')
                    ->label('Total Skor')
                    ->numeric(4)
                    ->sortable()
                    ->badge()
                    ->color(fn($state) => match (true) {
                        $state >= 0.8 => 'success',
                        $state >= 0.6 => 'warning',
                        default => 'danger'
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'diterima' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray'
                    })
                    ->icon(fn($state) => match ($state) {
                        'diterima' => 'heroicon-o-check-circle',
                        'ditolak' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-clock'
                    })
                    ->formatStateUsing(fn($state) => ucfirst($state)),

                TextColumn::make('created_at')
                    ->label('Tanggal Seleksi')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Seleksi')
                    ->options([
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                    ])
                    ->placeholder('Semua Status'),
            ])
            ->actions([
                // No actions for user panel - read only
            ])
            ->bulkActions([
                // Remove bulk actions for user panel
            ])
            ->defaultSort('ranking', 'asc')
            ->striped()
            ->paginated([10, 25, 50]);
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
            'index' => Pages\ListHasilRankings::route('/'),
        ];
    }

    // Disable create and edit for user panel
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
