<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\CalonMahasiswaResource\Pages;
use App\Models\CalonMahasiswa;
use App\Models\Kriteria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CalonMahasiswaResource extends Resource
{
    protected static ?string $model = CalonMahasiswa::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Data Kandidat (Backup)';

    protected static ?string $modelLabel = 'Kandidat';

    protected static ?string $pluralModelLabel = 'Data Kandidat';

    protected static ?string $navigationGroup = 'Monitoring';

    protected static ?int $navigationSort = 1;

    // Hide from navigation - keep for backup
    protected static bool $shouldRegisterNavigation = false;

    /**
     * Override default query untuk menggunakan natural sorting
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderByKodeNatural();
    }
    public static function form(Form $form): Form
    {
        // Ambil semua kriteria untuk form dinamis
        $kriteria = Kriteria::orderByKodeNatural()->get();

        $schema = [
            Forms\Components\TextInput::make('nama')
                ->label('Nama Lengkap Calon Mahasiswa')
                ->disabled(),
        ];

        // Tambahkan field untuk setiap kriteria secara dinamis
        if ($kriteria->isNotEmpty()) {
            $kriteriaFields = [];
            foreach ($kriteria as $k) {
                $kriteriaFields[] = Forms\Components\TextInput::make(strtolower($k->kode))
                    ->label($k->nama)
                    ->disabled();
            }

            $schema[] = Forms\Components\Grid::make(2)->schema($kriteriaFields);
        }

        return $form->schema($schema);
    }
    public static function table(Table $table): Table
    {
        // Ambil semua kriteria untuk kolom dinamis
        $kriteria = Kriteria::orderByKodeNatural()->get();

        $columns = [
            Tables\Columns\TextColumn::make('kode')
                ->label('Kode')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('nama')
                ->label('Nama Calon Mahasiswa')
                ->searchable()
                ->sortable()
                ->wrap(),
        ];

        // Tambahkan kolom untuk setiap kriteria secara dinamis
        foreach ($kriteria as $k) {
            $kodeField = strtolower($k->kode);

            if ($k->tipe === 'benefit') {
                // Untuk kriteria benefit: semakin tinggi semakin baik (hijau)
                $columns[] = Tables\Columns\TextColumn::make($kodeField)
                    ->label($k->nama)
                    ->sortable()
                    ->alignCenter()
                    ->formatStateUsing(function ($state) use ($k) {
                        if ($k->satuan === 'rupiah') {
                            return 'Rp ' . number_format($state, 0, ',', '.');
                        }
                        return $state . ($k->satuan ? ' ' . $k->satuan : '');
                    })
                    ->color(function ($state) {
                        $numericValue = (float) $state;
                        return $numericValue >= 80 ? 'success' : ($numericValue >= 60 ? 'warning' : 'danger');
                    });
            } else {
                // Untuk kriteria cost: semakin rendah semakin baik (hijau)
                $columns[] = Tables\Columns\TextColumn::make($kodeField)
                    ->label($k->nama)
                    ->sortable()
                    ->alignCenter()
                    ->formatStateUsing(function ($state) use ($k) {
                        if ($k->satuan === 'rupiah') {
                            return 'Rp ' . number_format($state, 0, ',', '.');
                        }
                        return $state . ($k->satuan ? ' ' . $k->satuan : '');
                    })
                    ->color(function ($state) {
                        $numericValue = (float) $state;
                        return $numericValue <= 2000000 ? 'success' : ($numericValue <= 5000000 ? 'warning' : 'danger');
                    });
            }
        }

        // Tambahkan kolom ranking dan tanggal
        $columns[] = Tables\Columns\BadgeColumn::make('hasil_seleksi.rank')
            ->label('Ranking')
            ->getStateUsing(function ($record) {
                $hasil = $record->hasilSeleksi;
                return $hasil ? '#' . $hasil->rank : 'Belum Dievaluasi';
            })
            ->color(fn(string $state): string => match (true) {
                str_contains($state, 'Belum') => 'gray',
                str_contains($state, '#1') => 'warning',
                (int) str_replace('#', '', $state) <= 10 => 'success',
                default => 'primary',
            });

        $columns[] = Tables\Columns\TextColumn::make('created_at')
            ->label('Tanggal Daftar')
            ->dateTime('d M Y')
            ->sortable();

        return $table->columns($columns)
            ->filters([
                Tables\Filters\SelectFilter::make('has_ranking')
                    ->label('Status Evaluasi')
                    ->options([
                        'evaluated' => 'Sudah Dievaluasi',
                        'not_evaluated' => 'Belum Dievaluasi',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] === 'evaluated',
                            fn(Builder $query): Builder => $query->whereHas('hasilSeleksi'),
                            fn(Builder $query): Builder => $query->when(
                                $data['value'] === 'not_evaluated',
                                fn(Builder $query): Builder => $query->whereDoesntHave('hasilSeleksi')
                            )
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Detail'),
            ])
            ->bulkActions([
                // Tidak ada bulk actions untuk user
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
            'index' => Pages\ListCalonMahasiswa::route('/'),
            'view' => Pages\ViewCalonMahasiswa::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // User tidak bisa create
    }

    public static function canEdit($record): bool
    {
        return false; // User tidak bisa edit
    }

    public static function canDelete($record): bool
    {
        return false; // User tidak bisa delete
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
