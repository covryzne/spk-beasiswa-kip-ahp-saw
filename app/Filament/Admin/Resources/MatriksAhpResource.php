<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MatriksAhpResource\Pages;
use App\Filament\Admin\Resources\MatriksAhpResource\RelationManagers;
use App\Models\MatriksAhp;
use App\Models\Kriteria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Grid;

class MatriksAhpResource extends Resource
{
    protected static ?string $model = MatriksAhp::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static ?string $navigationLabel = 'Matriks AHP';

    protected static ?string $modelLabel = 'Matriks AHP';

    protected static ?string $pluralModelLabel = 'Matriks AHP';

    protected static ?string $navigationGroup = 'Metode AHP';

    protected static ?int $navigationSort = 2;

    // Hide from navigation since it's integrated into Perhitungan Bobot AHP
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(2)->schema([
                Select::make('kriteria_1_id')
                    ->label('Kriteria Baris')
                    ->options(Kriteria::pluck('nama', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih kriteria untuk baris matriks'),
                Select::make('kriteria_2_id')
                    ->label('Kriteria Kolom')
                    ->options(Kriteria::pluck('nama', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih kriteria untuk kolom matriks'),
            ]),
            TextInput::make('nilai')
                ->label('Nilai Perbandingan')
                ->numeric()
                ->step(0.000001)
                ->required()
                ->placeholder('1, 3, 5, 7, 9 atau 1/3, 1/5, 1/7, 1/9')
                ->helperText('Nilai 1=sama penting, 3=sedikit lebih penting, 5=cukup penting, 7=sangat penting, 9=mutlak lebih penting')
                ->columnSpanFull(),
            Forms\Components\Placeholder::make('ahp_guide')
                ->label('Panduan Skala AHP')
                ->content('
                    • 1 = Kedua kriteria sama penting
                    • 3 = Kriteria baris sedikit lebih penting dari kolom
                    • 5 = Kriteria baris cukup penting dari kolom
                    • 7 = Kriteria baris sangat penting dari kolom
                    • 9 = Kriteria baris mutlak lebih penting dari kolom
                    • 1/3, 1/5, 1/7, 1/9 = Kebalikan dari nilai di atas
                ')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kriteria1.kode')
                    ->label('Kriteria Baris')
                    ->sortable(),
                TextColumn::make('kriteria1.nama')
                    ->label('Nama Kriteria Baris')
                    ->wrap()
                    ->limit(30),
                TextColumn::make('kriteria2.kode')
                    ->label('Kriteria Kolom')
                    ->sortable(),
                TextColumn::make('kriteria2.nama')
                    ->label('Nama Kriteria Kolom')
                    ->wrap()
                    ->limit(30),
                TextColumn::make('nilai')
                    ->label('Nilai Perbandingan')
                    ->numeric(decimalPlaces: 6)
                    ->sortable()
                    ->color(fn($state) => $state > 1 ? 'success' : ($state < 1 ? 'warning' : 'info')),
                TextColumn::make('interpretasi')
                    ->label('Interpretasi')
                    ->getStateUsing(function ($record) {
                        $nilai = (float) $record->nilai;
                        if ($nilai == 1) return 'Sama penting';
                        if ($nilai == 3) return 'Sedikit lebih penting';
                        if ($nilai == 5) return 'Cukup penting';
                        if ($nilai == 7) return 'Sangat penting';
                        if ($nilai == 9) return 'Mutlak lebih penting';
                        if ($nilai == 1 / 3) return 'Sedikit kurang penting';
                        if ($nilai == 1 / 5) return 'Cukup kurang penting';
                        if ($nilai == 1 / 7) return 'Sangat kurang penting';
                        if ($nilai == 1 / 9) return 'Mutlak kurang penting';
                        return 'Nilai intermediate';
                    })
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Sama penting' => 'info',
                        'Sedikit lebih penting', 'Sedikit kurang penting' => 'success',
                        'Cukup penting', 'Cukup kurang penting' => 'warning',
                        'Sangat penting', 'Sangat kurang penting' => 'danger',
                        'Mutlak lebih penting', 'Mutlak kurang penting' => 'gray',
                        default => 'primary'
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kriteria_1_id')
                    ->label('Filter Kriteria Baris')
                    ->options(Kriteria::pluck('nama', 'id')),
                Tables\Filters\SelectFilter::make('kriteria_2_id')
                    ->label('Filter Kriteria Kolom')
                    ->options(Kriteria::pluck('nama', 'id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('kriteria_1_id');
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
            'index' => Pages\ListMatriksAhps::route('/'),
            'create' => Pages\CreateMatriksAhp::route('/create'),
            'edit' => Pages\EditMatriksAhp::route('/{record}/edit'),
        ];
    }
}
