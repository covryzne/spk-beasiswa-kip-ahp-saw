<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KriteriaResource\Pages;
use App\Filament\Admin\Resources\KriteriaResource\RelationManagers;
use App\Models\Kriteria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class KriteriaResource extends Resource
{
    protected static ?string $model = Kriteria::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationLabel = 'Kriteria';

    protected static ?string $modelLabel = 'Kriteria';

    protected static ?string $pluralModelLabel = 'Kriteria';

    protected static ?string $navigationGroup = 'Setup Data';

    protected static ?int $navigationSort = 1;

    /**
     * Override default query untuk menggunakan natural sorting
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderByKodeNatural();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('kode')
                ->label('Kode Kriteria')
                ->required()
                ->maxLength(10)
                ->unique(ignoreRecord: true)
                ->placeholder('Contoh: C1, C2, C3')
                ->helperText('Kode unik untuk kriteria'),
            TextInput::make('nama')
                ->label('Nama Kriteria')
                ->required()
                ->maxLength(255)
                ->placeholder('Contoh: Penghasilan Orang Tua')
                ->helperText('Nama lengkap kriteria penilaian'),
            Select::make('jenis')
                ->label('Jenis Kriteria')
                ->options([
                    'Cost' => 'Cost (Nilai kecil lebih baik)',
                    'Benefit' => 'Benefit (Nilai besar lebih baik)',
                ])
                ->required()
                ->helperText('Cost: semakin kecil semakin prioritas (penghasilan, jarak). Benefit: semakin besar semakin prioritas (nilai, prestasi)'),
            Forms\Components\Textarea::make('deskripsi')
                ->label('Deskripsi Kriteria')
                ->maxLength(500)
                ->placeholder('Jelaskan kriteria ini dan cara penilaiannya')
                ->helperText('Penjelasan detail tentang kriteria dan skala penilaiannya')
                ->columnSpanFull(),
            TextInput::make('bobot')
                ->label('Bobot Kriteria')
                ->numeric()
                ->step(0.000000001)
                ->disabled()
                ->helperText('Bobot akan diisi otomatis dari perhitungan AHP'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->label('Kode')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nama')
                    ->label('Nama Kriteria')
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Cost' => 'danger',
                        'Benefit' => 'success',
                    }),
                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(),
                TextColumn::make('bobot')
                    ->label('Bobot AHP')
                    ->numeric(decimalPlaces: 6)
                    ->sortable()
                    ->placeholder('Belum dihitung')
                    ->color(fn($state) => $state ? 'success' : 'warning'),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ->defaultSort('kode', 'asc');
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
            'index' => Pages\ListKriterias::route('/'),
            'create' => Pages\CreateKriteria::route('/create'),
            'edit' => Pages\EditKriteria::route('/{record}/edit'),
        ];
    }
}
