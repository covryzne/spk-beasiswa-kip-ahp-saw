<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\DataKandidatResource\Pages;
use App\Models\CalonMahasiswa;
use App\Models\Kriteria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class DataKandidatResource extends Resource
{
    protected static ?string $model = CalonMahasiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Calon Mahasiswa';

    protected static ?string $modelLabel = 'Calon Mahasiswa';

    protected static ?string $pluralModelLabel = 'Data Calon Mahasiswa';

    protected static ?string $navigationGroup = 'Data Kandidat';

    protected static ?int $navigationSort = 1;
    /**
     * Override default query untuk menggunakan natural sorting dan eager loading
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderByKodeNatural()
            ->with('hasilSeleksi');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form disabled for user panel - read only
            ]);
    }

    public static function table(Table $table): Table
    {
        $columns = [
            TextColumn::make('kode')
                ->label('Kode')
                ->searchable()
                ->sortable()
                ->badge()
                ->color('primary'),
            TextColumn::make('nama')
                ->label('Nama Calon Mahasiswa')
                ->searchable()
                ->sortable()
                ->wrap()
                ->weight('bold'),
        ];

        // Add dynamic kriteria columns
        $kriteria = Kriteria::orderBy('kode')->get();
        foreach ($kriteria as $k) {
            $fieldName = strtolower($k->kode);

            switch (strtoupper($k->kode)) {
                case 'C1': // Penghasilan
                    $columns[] = TextColumn::make($fieldName)
                        ->label('Penghasilan')
                        ->formatStateUsing(fn($state): string => 'Rp ' . number_format($state, 0, ',', '.'))
                        ->sortable()
                        ->color(fn($state) => $state <= 2000000 ? 'success' : ($state <= 5000000 ? 'warning' : 'danger'));
                    break;

                case 'C2': // Tempat Tinggal
                    $columns[] = Tables\Columns\BadgeColumn::make($fieldName)
                        ->label('Lokasi')
                        ->formatStateUsing(fn($state): string => match ((string)$state) {
                            '1' => 'Daerah 3T',
                            '2' => 'Pedesaan Jauh',
                            '3' => 'Pedesaan Dekat',
                            '4' => 'Pinggiran Kota',
                            '5' => 'Pusat Kota',
                            default => 'Unknown'
                        })
                        ->color(fn($state) => match ((string)$state) {
                            '1' => 'success',
                            '2' => 'info',
                            '3' => 'warning',
                            '4' => 'warning',
                            '5' => 'danger',
                            default => 'gray'
                        });
                    break;

                default: // C3, C4, C5 - Numeric scores
                    $shortLabel = str_replace(['Hasil ', 'Rata-rata ', 'Tes ', ' Akademik', ' SMA'], '', $k->nama);
                    $columns[] = TextColumn::make($fieldName)
                        ->label($shortLabel)
                        ->sortable()
                        ->formatStateUsing(fn($state): string => number_format($state, 1))
                        ->color(fn($state) => $state >= 75 ? 'success' : ($state >= 60 ? 'warning' : 'danger'));
                    break;
            }
        }

        return $table
            ->columns($columns)
            ->filters([
                SelectFilter::make('c2')
                    ->label('Filter Lokasi')
                    ->options([
                        1 => 'Daerah 3T',
                        2 => 'Pedesaan Jauh',
                        3 => 'Pedesaan Dekat',
                        4 => 'Pinggiran Kota',
                        5 => 'Pusat Kota',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Remove bulk actions for user panel
            ])
            ->defaultSort('kode', 'asc')
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
            'index' => Pages\ListDataKandidats::route('/'),
            'view' => Pages\ViewDataKandidat::route('/{record}'),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        // Ambil semua kriteria untuk infolist dinamis
        $kriteria = Kriteria::orderByKodeNatural()->get();

        $schema = [
            Infolists\Components\Section::make('Informasi Calon Mahasiswa')
                ->schema([
                    Infolists\Components\Grid::make(2)
                        ->schema([
                            Infolists\Components\TextEntry::make('kode')
                                ->label('Kode Alternatif'),
                            Infolists\Components\TextEntry::make('nama')
                                ->label('Nama Lengkap'),
                        ]),
                ]),
        ];

        // Tambahkan section untuk kriteria
        if ($kriteria->isNotEmpty()) {
            $kriteriaEntries = [];

            foreach ($kriteria as $k) {
                $kodeField = strtolower($k->kode);

                $kriteriaEntries[] = Infolists\Components\TextEntry::make($kodeField)
                    ->label($k->nama . ' (' . $k->kode . ')')
                    ->formatStateUsing(function ($state) use ($k) {
                        if ($k->satuan === 'rupiah') {
                            return 'Rp ' . number_format($state, 0, ',', '.');
                        }
                        return $state . ($k->satuan ? ' ' . $k->satuan : '');
                    })
                    ->color(function ($state) use ($k) {
                        $numericValue = (float) $state;

                        if ($k->tipe === 'benefit') {
                            // Benefit: semakin tinggi semakin baik
                            return $numericValue >= 80 ? 'success' : ($numericValue >= 60 ? 'warning' : 'danger');
                        } else {
                            // Cost: semakin rendah semakin baik (khusus untuk penghasilan)
                            if ($k->satuan === 'rupiah') {
                                return $numericValue <= 2000000 ? 'success' : ($numericValue <= 5000000 ? 'warning' : 'danger');
                            }
                            return $numericValue <= 60 ? 'success' : ($numericValue <= 80 ? 'warning' : 'danger');
                        }
                    })
                    ->badge();
            }

            $schema[] = Infolists\Components\Section::make('Detail Nilai Kriteria')
                ->schema([
                    Infolists\Components\Grid::make(2)
                        ->schema($kriteriaEntries),
                ]);
        }

        // Tambahkan section hasil seleksi jika ada
        $schema[] = Infolists\Components\Section::make('Hasil Seleksi')
            ->schema([
                Infolists\Components\Grid::make(3)
                    ->schema([
                        Infolists\Components\TextEntry::make('hasilSeleksi.rank')
                            ->label('Ranking')
                            ->formatStateUsing(fn($state) => $state ? '#' . $state : 'Belum Dievaluasi')
                            ->color(fn($state) => $state ? ($state <= 10 ? 'success' : 'primary') : 'gray')
                            ->badge(),
                        Infolists\Components\TextEntry::make('hasilSeleksi.skor')
                            ->label('Skor Akhir')
                            ->formatStateUsing(fn($state) => $state ? number_format($state, 4) : 'Belum Dievaluasi')
                            ->color('info')
                            ->badge(),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Tanggal Daftar')
                            ->dateTime('d F Y, H:i')
                            ->color('gray'),
                    ]),
            ]);

        return $infolist->schema($schema);
    }
}
