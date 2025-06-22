<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CalonMahasiswaResource\Pages;
use App\Filament\Admin\Resources\CalonMahasiswaResource\RelationManagers;
use App\Models\CalonMahasiswa;
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
use Filament\Forms\Components\Section;

class CalonMahasiswaResource extends Resource
{
    protected static ?string $model = CalonMahasiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Calon Mahasiswa';

    protected static ?string $modelLabel = 'Calon Mahasiswa';

    protected static ?string $pluralModelLabel = 'Calon Mahasiswa';

    protected static ?string $navigationGroup = 'Data Alternatif';

    protected static ?int $navigationSort = 4;

    /**
     * Override default query untuk menggunakan natural sorting
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderByKodeNatural();
    }

    // app/Filament/Admin/Resources/CalonMahasiswaResource.php
    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Data Dasar')
                ->description('Informasi dasar calon mahasiswa')
                ->schema([
                    TextInput::make('kode')
                        ->label('Kode Mahasiswa')
                        ->required()
                        ->maxLength(10)
                        ->unique(ignoreRecord: true)
                        ->placeholder('Contoh: A01, A02, dst.')
                        ->helperText('Kode unik untuk identifikasi calon mahasiswa'),

                    TextInput::make('nama')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Masukkan nama lengkap calon mahasiswa')
                        ->helperText('Nama lengkap sesuai dokumen resmi'),
                ]),

            Section::make('Nilai Kriteria Penilaian')
                ->description('Input nilai berdasarkan kriteria yang telah ditetapkan')
                ->schema(static::generateKriteriaFields()),

            Section::make('Catatan Tambahan')
                ->description('Informasi pendukung (opsional)')
                ->schema([
                    Forms\Components\Textarea::make('catatan')
                        ->label('Catatan')
                        ->placeholder('Catatan tambahan tentang calon mahasiswa (opsional)')
                        ->maxLength(1000)
                        ->rows(3),
                ])
                ->collapsible()
                ->collapsed(),
        ]);
    }

    /**
     * Generate dynamic form fields based on existing kriteria
     */
    private static function generateKriteriaFields(): array
    {
        $kriteria = Kriteria::orderBy('kode')->get();
        $fields = [];

        foreach ($kriteria as $k) {
            $fieldName = strtolower($k->kode); // c1, c2, c3, etc.

            // Generate field based on criteria type and name
            $field = static::createFieldForKriteria($k, $fieldName);

            if ($field) {
                $fields[] = $field;
            }
        }

        return $fields;
    }

    /**
     * Create appropriate field type based on criteria
     */
    private static function createFieldForKriteria(Kriteria $kriteria, string $fieldName)
    {
        $label = $kriteria->nama;
        $description = $kriteria->deskripsi ?? '';
        $jenis = $kriteria->jenis;

        // Dynamic field generation based on nama kriteria (content-based detection)
        if (stripos($label, 'penghasilan') !== false || stripos($label, 'gaji') !== false) {
            // Penghasilan - numeric field for money
            return TextInput::make($fieldName)
                ->label($label . ' (Rp/bulan)')
                ->numeric()
                ->step(1000)
                ->minValue(0)
                ->required()
                ->placeholder('Contoh: 2500000')
                ->helperText($jenis . ' - ' . $description . ' | Penghasilan kotor per bulan dalam rupiah');
        } elseif (stripos($label, 'tempat tinggal') !== false || stripos($label, 'lokasi') !== false || stripos($label, 'kondisi') !== false) {
            // Location - select field
            return Select::make($fieldName)
                ->label($label)
                ->options([
                    1 => '1 - Daerah Terpencil/3T (Terdepan, Terluar, Tertinggal)',
                    2 => '2 - Pedesaan Jauh dari Kota',
                    3 => '3 - Pedesaan Dekat Kota',
                    4 => '4 - Pinggiran Kota',
                    5 => '5 - Pusat Kota',
                ])
                ->required()
                ->helperText($jenis . ' - ' . $description . ' | Nilai 1 = prioritas tertinggi (daerah 3T), nilai 5 = prioritas terendah (pusat kota)');
        } elseif (stripos($label, 'tes') !== false || stripos($label, 'nilai') !== false || stripos($label, 'wawancara') !== false || stripos($label, 'prestasi') !== false || stripos($label, 'rapor') !== false) {
            // Test scores - 0-100 range
            return TextInput::make($fieldName)
                ->label($label)
                ->numeric()
                ->step(0.1)
                ->minValue(0)
                ->maxValue(100)
                ->required()
                ->placeholder('0-100')
                ->helperText($jenis . ' - ' . $description . ' | Nilai dalam skala 0-100');
        } elseif (stripos($label, 'usia') !== false || stripos($label, 'umur') !== false) {
            // Age - integer input
            return TextInput::make($fieldName)
                ->label($label)
                ->numeric()
                ->step(1)
                ->minValue(16)
                ->maxValue(35)
                ->required()
                ->placeholder('17-25')
                ->helperText($jenis . ' - ' . $description . ' | Usia dalam tahun');
        } elseif (stripos($label, 'jarak') !== false) {
            // Distance - decimal input
            return TextInput::make($fieldName)
                ->label($label . ' (km)')
                ->numeric()
                ->step(0.1)
                ->minValue(0)
                ->required()
                ->placeholder('Contoh: 15.5')
                ->helperText($jenis . ' - ' . $description . ' | Jarak dalam kilometer');
        } else {
            // Generic numeric field for other criteria
            return TextInput::make($fieldName)
                ->label($label)
                ->numeric()
                ->step(0.1)
                ->required()
                ->placeholder('Masukkan nilai')
                ->helperText($jenis . ' - ' . $description);
        }
    }

    public static function table(Table $table): Table
    {
        $columns = [
            Tables\Columns\TextColumn::make('kode')
                ->label('Kode')
                ->searchable()
                ->sortable()
                ->badge()
                ->color('primary'),
            Tables\Columns\TextColumn::make('nama')
                ->label('Nama Calon Mahasiswa')
                ->searchable()
                ->sortable()
                ->wrap()
                ->weight('bold'),
        ];

        // Add dynamic kriteria columns
        $columns = array_merge($columns, static::generateKriteriaColumns());

        // Add timestamp column
        $columns[] = Tables\Columns\TextColumn::make('created_at')
            ->label('Tanggal Daftar')
            ->dateTime('d M Y')
            ->sortable()
            ->toggleable();

        return $table
            ->columns($columns)
            ->filters([
                Tables\Filters\SelectFilter::make('c2')
                    ->label('Filter Lokasi')
                    ->options([
                        1 => 'Daerah 3T',
                        2 => 'Pedesaan Jauh',
                        3 => 'Pedesaan Dekat',
                        4 => 'Pinggiran Kota',
                        5 => 'Pusat Kota',
                    ]),
                Tables\Filters\Filter::make('penghasilan_rendah')
                    ->label('Penghasilan ≤ 2 Juta')
                    ->query(fn(Builder $query): Builder => $query->where('c1', '<=', 2000000)),
                Tables\Filters\Filter::make('nilai_tinggi')
                    ->label('Rata-rata Rapor ≥ 80')
                    ->query(fn(Builder $query): Builder => $query->where('c5', '>=', 80)),
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

    /**
     * Generate dynamic table columns based on existing kriteria
     */
    private static function generateKriteriaColumns(): array
    {
        $kriteria = Kriteria::orderBy('kode')->get();
        $columns = [];

        foreach ($kriteria as $k) {
            $fieldName = strtolower($k->kode); // c1, c2, c3, etc.

            // Generate column based on criteria type
            $column = static::createColumnForKriteria($k, $fieldName);

            if ($column) {
                $columns[] = $column;
            }
        }

        return $columns;
    }

    /**
     * Create appropriate column type based on criteria
     */
    private static function createColumnForKriteria(Kriteria $kriteria, string $fieldName)
    {
        $label = $kriteria->nama;
        $jenis = $kriteria->jenis;
        $kriteriaName = strtolower($kriteria->nama);

        // Dynamic handling based on criteria name patterns
        if (str_contains($kriteriaName, 'penghasilan') || str_contains($kriteriaName, 'gaji') || str_contains($kriteriaName, 'income')) {
            // Income/salary - format as currency
            return Tables\Columns\TextColumn::make($fieldName)
                ->label('Penghasilan')
                ->sortable()
                ->formatStateUsing(fn($state): string => $state ? 'Rp ' . number_format($state, 0, ',', '.') : 'N/A')
                ->color(fn($state): string => $state <= 2000000 ? 'success' : ($state <= 5000000 ? 'warning' : 'danger'))
                ->tooltip(fn($state): string => 'Penghasilan: Rp ' . number_format($state, 0, ',', '.'));
        } elseif (str_contains($kriteriaName, 'tempat tinggal') || str_contains($kriteriaName, 'lokasi') || str_contains($kriteriaName, 'kondisi')) {
            // Location - show as badge
            return Tables\Columns\BadgeColumn::make($fieldName)
                ->label('Lokasi')
                ->formatStateUsing(fn($state): string => match ((string)$state) {
                    '1' => 'Daerah 3T',
                    '2' => 'Pedesaan Jauh',
                    '3' => 'Pedesaan Dekat',
                    '4' => 'Pinggiran Kota',
                    '5' => 'Pusat Kota',
                    default => 'Unknown'
                })
                ->color(fn($state): string => match ((string)$state) {
                    '1' => 'success',
                    '2' => 'info',
                    '3' => 'warning',
                    '4' => 'warning',
                    '5' => 'danger',
                    default => 'gray'
                });
        } elseif (str_contains($kriteriaName, 'prestasi') || str_contains($kriteriaName, 'wawancara') || str_contains($kriteriaName, 'nilai') || str_contains($kriteriaName, 'grade')) {
            // Academic scores, achievements, etc.
            $shortLabel = str_replace(['Hasil ', 'Rata-rata ', 'Tes ', ' Akademik', ' SMA'], '', $label);
            return Tables\Columns\TextColumn::make($fieldName)
                ->label($shortLabel)
                ->sortable()
                ->color(fn($state): string => $state >= 80 ? 'success' : ($state >= 60 ? 'warning' : 'danger'))
                ->formatStateUsing(fn($state): string => $state ? number_format($state, 1) : 'N/A')
                ->tooltip(fn($state): string => $label . ': ' . number_format($state, 2));
        } elseif (str_contains($kriteriaName, 'usia') || str_contains($kriteriaName, 'umur') || str_contains($kriteriaName, 'age')) {
            // Age
            return Tables\Columns\TextColumn::make($fieldName)
                ->label('Usia')
                ->sortable()
                ->formatStateUsing(fn($state): string => $state ? $state . ' tahun' : 'N/A')
                ->tooltip(fn($state): string => 'Usia: ' . $state . ' tahun');
        } elseif (str_contains($kriteriaName, 'jarak') || str_contains($kriteriaName, 'distance')) {
            // Distance
            return Tables\Columns\TextColumn::make($fieldName)
                ->label('Jarak')
                ->sortable()
                ->formatStateUsing(fn($state): string => $state ? number_format($state, 1) . ' km' : 'N/A')
                ->tooltip(fn($state): string => 'Jarak: ' . number_format($state, 1) . ' km');
        } else {
            // Generic numeric column for other criteria
            return Tables\Columns\TextColumn::make($fieldName)
                ->label($kriteria->kode)
                ->sortable()
                ->formatStateUsing(fn($state): string => $state ? number_format($state, 1) : 'N/A')
                ->tooltip($label);
        }
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
            'index' => Pages\ListCalonMahasiswas::route('/'),
            'create' => Pages\CreateCalonMahasiswa::route('/create'),
            'edit' => Pages\EditCalonMahasiswa::route('/{record}/edit'),
        ];
    }
}
