<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\HasilSeleksiResource\Pages;
use App\Models\HasilSeleksi;
use App\Services\SawService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class HasilSeleksiResource extends Resource
{
    protected static ?string $model = HasilSeleksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationLabel = 'Hasil Ranking';

    protected static ?string $modelLabel = 'Hasil Seleksi';

    protected static ?string $pluralModelLabel = 'Hasil Ranking Beasiswa';

    protected static ?string $navigationGroup = 'Perankingan';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('calon_mahasiswa_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('skor')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('ranking')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('ranking', 'asc')
            ->columns([
                // Ranking Badge
                TextColumn::make('ranking')
                    ->label('Rank')
                    ->alignCenter()
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match (true) {
                        $state == '1' => 'warning', // Gold
                        $state == '2' => 'gray',    // Silver  
                        $state == '3' => 'danger',  // Bronze
                        default => 'secondary',
                    })
                    ->icon(fn(string $state): string => match (true) {
                        $state == '1' => 'heroicon-m-trophy',
                        $state == '2' => 'heroicon-m-star',
                        $state == '3' => 'heroicon-m-star',
                        default => 'heroicon-m-hashtag',
                    })
                    ->weight(FontWeight::Bold)
                    ->formatStateUsing(fn(string $state): string => "#{$state}"),

                // Kode Mahasiswa
                TextColumn::make('calonMahasiswa.kode')
                    ->label('Kode')
                    ->alignCenter()
                    ->badge()
                    ->color('info')
                    ->weight(FontWeight::SemiBold)
                    ->searchable(),

                // Nama Mahasiswa
                TextColumn::make('calonMahasiswa.nama')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->weight(FontWeight::Medium)
                    ->wrap(),

                // Skor SAW
                TextColumn::make('skor')
                    ->label('Skor SAW')
                    ->alignCenter()
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match (true) {
                        (float) $state >= 0.8 => 'success',
                        (float) $state >= 0.6 => 'warning',
                        default => 'danger',
                    })
                    ->weight(FontWeight::Bold)
                    ->formatStateUsing(fn(string $state): string => number_format((float) $state, 4)),

                // Status
                TextColumn::make('status')
                    ->label('Status')
                    ->alignCenter()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'diterima' => 'success',
                        'ditolak' => 'danger',
                        default => 'secondary',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'diterima' => 'heroicon-m-check-circle',
                        'ditolak' => 'heroicon-m-x-circle',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->weight(FontWeight::Bold)
                    ->formatStateUsing(fn(string $state): string => strtoupper($state)),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                    ]),
                Tables\Filters\Filter::make('top_ranks')
                    ->label('Top 3 Ranking')
                    ->query(fn(Builder $query): Builder => $query->where('ranking', '<=', 3)),
            ])
            ->actions([
                // No edit action - data is calculated, not manually editable
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            HasilSeleksiResource\Widgets\HasilRankingStatsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHasilSeleksis::route('/'),
            // No edit page - data is calculated from SAW algorithm
        ];
    }
}
