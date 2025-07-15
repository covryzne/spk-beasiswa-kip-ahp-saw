<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CalonMahasiswaResource\Pages;
use App\Filament\Admin\Resources\CalonMahasiswaResource\RelationManagers;
use App\Models\CalonMahasiswa;
use App\Models\Kriteria;
use App\Models\DataMahasiswa;
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

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Pilih Data Mahasiswa')
                ->description('Pilih dari data master mahasiswa atau input manual')
                ->schema([
                    Select::make('data_mahasiswa_id')
                        ->label('Data Mahasiswa')
                        ->options(DataMahasiswa::all()->pluck('display_name', 'id'))
                        ->searchable()
                        ->nullable()
                        ->placeholder('Pilih data mahasiswa atau kosongkan untuk input manual')
                        ->helperText('Jika dipilih, data akan otomatis terisi dari master data mahasiswa')
                        ->live()
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            if ($state) {
                                $dataMahasiswa = DataMahasiswa::find($state);
                                if ($dataMahasiswa) {
                                    // Auto-populate basic data
                                    $set('nama', $dataMahasiswa->nama);
                                    $set('kode', $dataMahasiswa->generateKodeCalonMahasiswa());

                                    // Auto-populate criteria values using raw mapping
                                    $kriteria = Kriteria::orderBy('kode')->get();

                                    foreach ($kriteria as $k) {
                                        $fieldName = strtolower($k->kode);

                                        // Use raw mapped value for form (not converted to numeric)
                                        $rawValue = $dataMahasiswa->getRawMappedValueForKriteria($k->nama);
                                        if ($rawValue !== null) {
                                            $set($fieldName, $rawValue);
                                        }
                                    }
                                }
                            }
                        }),
                ])
                ->collapsible()
                ->collapsed(fn($record) => $record !== null), // Collapsed on edit, expanded on create

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
        $kriteriaName = strtolower($kriteria->nama);

        // Check for specific criteria that need dropdown options
        if (str_contains($kriteriaName, 'komitmen') || str_contains($kriteriaName, 'kuliah')) {
            // C5: Komitmen Kuliah - dropdown options
            return Select::make($fieldName)
                ->label($label)
                ->options([
                    'Kurang Berkomitmen' => 'Kurang Berkomitmen',
                    'Cukup Berkomitmen' => 'Cukup Berkomitmen',
                    'Berkomitmen' => 'Berkomitmen',
                    'Sangat Berkomitmen' => 'Sangat Berkomitmen',
                ])
                ->required()
                ->helperText($jenis . ' - Tingkat komitmen untuk menyelesaikan kuliah')
                ->afterStateHydrated(function (Select $component, $state, $record) {
                    if ($record && $record->dataMahasiswa) {
                        // Always prioritize original DataMahasiswa value for consistent display
                        $rawValue = $record->dataMahasiswa->komitmen;
                        if ($rawValue !== null) {
                            $component->state($rawValue);
                            return;
                        }

                        // Only if no DataMahasiswa value, check CalonMahasiswa edited state
                        $rawValue = $record->dataMahasiswa->komitmen;
                        if ($rawValue !== null) {
                            $component->state($rawValue);
                        }
                    }
                })
                ->dehydrateStateUsing(function ($state) {
                    // Convert dropdown string back to numeric for saving
                    if ($state !== null) {
                        $dataMahasiswa = new \App\Models\DataMahasiswa();
                        return $dataMahasiswa->convertToNumericValue($state, 'komitmen');
                    }
                    return $state;
                });
        } elseif (str_contains($kriteriaName, 'aset') || str_contains($kriteriaName, 'ekonomi')) {
            // C6: Aset Keluarga/Kondisi Ekonomi - dropdown options
            return Select::make($fieldName)
                ->label($label)
                ->options([
                    'Tidak Ada' => 'Tidak Ada',
                    'Sangat Sedikit' => 'Sangat Sedikit',
                    'Sedikit' => 'Sedikit',
                    'Cukup' => 'Cukup',
                    'Banyak' => 'Banyak',
                    'Sangat Banyak' => 'Sangat Banyak',
                ])
                ->required()
                ->helperText($jenis . ' - Kondisi ekonomi dan kepemilikan aset keluarga')
                ->afterStateHydrated(function (Select $component, $state, $record) {
                    if ($record && $record->dataMahasiswa) {
                        // Always prioritize original DataMahasiswa value for consistent display
                        $rawValue = $record->dataMahasiswa->kondisi_ekonomi;
                        if ($rawValue !== null) {
                            $component->state($rawValue);
                            return;
                        }

                        // Only if no DataMahasiswa value, check CalonMahasiswa edited state
                        $rawValue = $record->dataMahasiswa->kondisi_ekonomi;
                        if ($rawValue !== null) {
                            $component->state($rawValue);
                        }
                    }
                })
                ->dehydrateStateUsing(function ($state) {
                    // Convert dropdown string back to numeric for saving
                    if ($state !== null) {
                        $dataMahasiswa = new \App\Models\DataMahasiswa();
                        return $dataMahasiswa->convertToNumericValue($state, 'kondisi_ekonomi');
                    }
                    return $state;
                });
        } elseif (str_contains($kriteriaName, 'kartu') || str_contains($kriteriaName, 'bantuan') || str_contains($kriteriaName, 'sosial')) {
            // C7: Kartu Bantuan Sosial - dropdown options
            return Select::make($fieldName)
                ->label($label)
                ->options([
                    'Tidak Ada' => 'Tidak Ada',
                    'KIP' => 'KIP',
                    'PKH' => 'PKH',
                    'KKS' => 'KKS',
                    'KIP + PKH' => 'KIP + PKH',
                    'KIP + KKS' => 'KIP + KKS',
                    'PKH + KKS' => 'PKH + KKS',
                    'Lengkap KIP + PKH + KKS' => 'Lengkap KIP + PKH + KKS',
                ])
                ->required()
                ->helperText($jenis . ' - Kepemilikan kartu bantuan sosial (KIP, PKH, KKS)')
                ->afterStateHydrated(function (Select $component, $state, $record) {
                    if ($record && $record->dataMahasiswa) {
                        // Always prioritize original DataMahasiswa value for consistent display
                        $rawValue = $record->dataMahasiswa->kip_status;
                        if ($rawValue !== null) {
                            $component->state($rawValue);
                            return;
                        }

                        // Only if no DataMahasiswa value, check CalonMahasiswa edited state
                        $rawValue = $record->dataMahasiswa->kip_status;
                        if ($rawValue !== null) {
                            $component->state($rawValue);
                        }
                    }
                })
                ->dehydrateStateUsing(function ($state) {
                    // Convert dropdown string back to numeric for saving
                    if ($state !== null) {
                        $dataMahasiswa = new \App\Models\DataMahasiswa();
                        return $dataMahasiswa->convertToNumericValue($state, 'kip_status');
                    }
                    return $state;
                });
        } elseif (str_contains($kriteriaName, 'kondisi') || str_contains($kriteriaName, 'tempat tinggal')) {
            // C2: Kondisi Tempat Tinggal - dropdown options  
            return Select::make($fieldName)
                ->label($label)
                ->options([
                    'Sangat Kurang' => 'Sangat Kurang',
                    'Kurang' => 'Kurang',
                    'Cukup' => 'Cukup',
                    'Baik' => 'Baik',
                    'Sangat Baik' => 'Sangat Baik',
                ])
                ->required()
                ->helperText($jenis . ' - Kondisi tempat tinggal saat ini')
                ->afterStateHydrated(function (Select $component, $state, $record) {
                    if ($record && $record->dataMahasiswa) {
                        // Always prioritize original DataMahasiswa value for consistent display
                        $rawValue = $record->dataMahasiswa->kondisi_rumah;
                        if ($rawValue !== null) {
                            $component->state($rawValue);
                            return;
                        }

                        // Only if no DataMahasiswa value, check CalonMahasiswa edited state
                        $rawValue = $record->dataMahasiswa->kondisi_rumah;
                        if ($rawValue !== null) {
                            $component->state($rawValue);
                        }
                    }
                })
                ->dehydrateStateUsing(function ($state) {
                    // Convert dropdown string back to numeric for saving
                    if ($state !== null) {
                        $dataMahasiswa = new \App\Models\DataMahasiswa();
                        return $dataMahasiswa->convertToNumericValue($state, 'kondisi_rumah');
                    }
                    return $state;
                });
        } elseif (str_contains($kriteriaName, 'pekerjaan') || str_contains($kriteriaName, 'kerja')) {
            // C4: Pekerjaan Orang Tua - dropdown options
            return Select::make($fieldName)
                ->label($label)
                ->options([
                    'Tidak Bekerja' => 'Tidak Bekerja',
                    'Pengangguran' => 'Pengangguran',
                    'Buruh Harian' => 'Buruh Harian',
                    'Petani' => 'Petani',
                    'Pedagang Kecil' => 'Pedagang Kecil',
                    'Karyawan' => 'Karyawan',
                    'Wiraswasta' => 'Wiraswasta',
                    'PNS' => 'PNS',
                ])
                ->required()
                ->helperText($jenis . ' - Jenis pekerjaan orang tua/wali')
                ->afterStateHydrated(function (Select $component, $state, $record) {
                    if ($record && $record->dataMahasiswa) {
                        // Always prioritize original DataMahasiswa value for consistent display
                        $rawValue = $record->dataMahasiswa->pekerjaan_orang_tua;
                        if ($rawValue !== null) {
                            $component->state($rawValue);
                            return;
                        }

                        // Only if no DataMahasiswa value, check CalonMahasiswa edited state
                        $rawValue = $record->dataMahasiswa->pekerjaan_orang_tua;
                        if ($rawValue !== null) {
                            $component->state($rawValue);
                        }
                    }
                })
                ->dehydrateStateUsing(function ($state) {
                    // Convert dropdown string back to numeric for saving
                    if ($state !== null) {
                        $dataMahasiswa = new \App\Models\DataMahasiswa();
                        return $dataMahasiswa->convertToNumericValue($state, 'pekerjaan_orang_tua');
                    }
                    return $state;
                });
        } elseif (stripos($label, 'penghasilan') !== false) {
            // Special handling for money fields
            return TextInput::make($fieldName)
                ->label($label . ' (Rp/bulan)')
                ->numeric()
                ->step(1000)
                ->minValue(0)
                ->required()
                ->helperText($jenis);
        } elseif (stripos($label, 'prestasi') !== false) {
            // Special handling for prestasi (achievement score)
            return TextInput::make($fieldName)
                ->label($label)
                ->numeric()
                ->step(0.1)
                ->minValue(0)
                ->maxValue(100)
                ->required()
                ->helperText($jenis . ' - ' . $description . ' | Nilai dalam skala 0-100');
        } else {
            // Default numeric input for other criteria
            return TextInput::make($fieldName)
                ->label($label)
                ->numeric()
                ->step(0.1)
                ->minValue(0)
                ->required()
                ->helperText($jenis);
        }
    }

    /**
     * Get helper text for dropdown fields
     */
    private static function getDropdownHelperText(string $label): string
    {
        $label = strtolower($label);

        if (str_contains($label, 'kondisi') || str_contains($label, 'tempat tinggal')) {
            return 'Nilai 1 = prioritas tertinggi (daerah 3T), nilai 5 = prioritas terendah (pusat kota)';
        }

        if (str_contains($label, 'pekerjaan') || str_contains($label, 'kerja')) {
            return 'Status pekerjaan saat ini';
        }

        if (str_contains($label, 'dukungan') || str_contains($label, 'support')) {
            return 'Tingkat dukungan dari keluarga';
        }

        return 'Pilih sesuai kondisi yang tepat';
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
            ->defaultSort('created_at', 'desc') // Show newest records first (real data 2024)
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
            ->headerActions([
                Tables\Actions\Action::make('print_all_pdf')
                    ->label('Print All Data')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn(): string => route('calon-mahasiswa.print-all'))
                    ->openUrlInNewTab(),
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
                ->label($label) // Use dynamic label from kriteria
                ->sortable()
                ->formatStateUsing(fn($state): string => $state ? 'Rp ' . number_format($state, 0, ',', '.') : 'N/A')
                ->color(fn($state): string => $state <= 2000000 ? 'success' : ($state <= 5000000 ? 'warning' : 'danger'))
                ->tooltip(fn($state): string => 'Penghasilan: Rp ' . number_format($state, 0, ',', '.'));
        } elseif (str_contains($kriteriaName, 'tempat tinggal') || str_contains($kriteriaName, 'lokasi') || str_contains($kriteriaName, 'kondisi')) {
            // Location - show raw value from DataMahasiswa
            return Tables\Columns\BadgeColumn::make($fieldName)
                ->label($label) // Use dynamic label from kriteria
                ->formatStateUsing(function ($state, $record) {
                    // Get raw value from DataMahasiswa if available
                    if ($record->dataMahasiswa) {
                        return $record->dataMahasiswa->kondisi_rumah ?? 'N/A';
                    }
                    // Fallback to numeric mapping if no DataMahasiswa
                    return match ((string)$state) {
                        '1' => 'Sangat Kurang',
                        '2' => 'Kurang',
                        '3' => 'Cukup',
                        '4' => 'Baik',
                        '5' => 'Sangat Baik',
                        default => 'N/A'
                    };
                })
                ->color(function ($state, $record) {
                    // Color based on raw value priority (Cost criteria)
                    if ($record->dataMahasiswa) {
                        return match ($record->dataMahasiswa->kondisi_rumah) {
                            'Sangat Kurang' => 'success', // High priority
                            'Kurang' => 'info',
                            'Cukup' => 'warning',
                            'Baik' => 'warning',
                            'Sangat Baik' => 'danger', // Low priority
                            default => 'gray'
                        };
                    }
                    return 'gray';
                });
        } elseif (str_contains($kriteriaName, 'prestasi') || str_contains($kriteriaName, 'wawancara') || str_contains($kriteriaName, 'nilai') || str_contains($kriteriaName, 'grade')) {
            // Academic scores, achievements, etc.
            return Tables\Columns\TextColumn::make($fieldName)
                ->label($label) // Use full label from kriteria
                ->sortable()
                ->color(fn($state): string => $state >= 80 ? 'success' : ($state >= 60 ? 'warning' : 'danger'))
                ->formatStateUsing(fn($state): string => $state ? number_format($state, 1) : 'N/A')
                ->tooltip(fn($state): string => $label . ': ' . number_format($state, 2));
        } elseif (str_contains($kriteriaName, 'usia') || str_contains($kriteriaName, 'umur') || str_contains($kriteriaName, 'age')) {
            // Age
            return Tables\Columns\TextColumn::make($fieldName)
                ->label($label) // Use dynamic label from kriteria
                ->sortable()
                ->formatStateUsing(fn($state): string => $state ? $state . ' tahun' : 'N/A')
                ->tooltip(fn($state): string => 'Usia: ' . $state . ' tahun');
        } elseif (str_contains($kriteriaName, 'jarak') || str_contains($kriteriaName, 'distance')) {
            // Distance
            return Tables\Columns\TextColumn::make($fieldName)
                ->label($label) // Use dynamic label from kriteria
                ->sortable()
                ->formatStateUsing(fn($state): string => $state ? number_format($state, 1) . ' km' : 'N/A')
                ->tooltip(fn($state): string => 'Jarak: ' . number_format($state, 1) . ' km');
        } elseif (str_contains($kriteriaName, 'pekerjaan') || str_contains($kriteriaName, 'kerja')) {
            // Pekerjaan Orang Tua - show raw value from DataMahasiswa
            return Tables\Columns\BadgeColumn::make($fieldName)
                ->label($label)
                ->formatStateUsing(function ($state, $record) {
                    if ($record->dataMahasiswa) {
                        return $record->dataMahasiswa->pekerjaan_orang_tua ?? 'N/A';
                    }
                    // Fallback to numeric mapping
                    return match ((string)$state) {
                        '1' => 'Tidak Bekerja',
                        '2' => 'Petani',
                        '3' => 'Pedagang Kecil',
                        '4' => 'Karyawan',
                        '5' => 'PNS',
                        default => 'N/A'
                    };
                })
                ->color(function ($state, $record) {
                    if ($record->dataMahasiswa) {
                        return match ($record->dataMahasiswa->pekerjaan_orang_tua) {
                            'Tidak Bekerja', 'Pengangguran' => 'success', // High priority
                            'Petani', 'Buruh Harian' => 'info',
                            'Pedagang Kecil' => 'warning',
                            'Karyawan', 'Wiraswasta' => 'warning',
                            'PNS' => 'danger', // Low priority
                            default => 'gray'
                        };
                    }
                    return 'gray';
                });
        } elseif (str_contains($kriteriaName, 'dukungan') || str_contains($kriteriaName, 'support')) {
            // Dukungan Orang Tua - show raw value from DataMahasiswa
            return Tables\Columns\BadgeColumn::make($fieldName)
                ->label($label)
                ->formatStateUsing(function ($state, $record) {
                    if ($record->dataMahasiswa) {
                        return $record->dataMahasiswa->support_orang_tua ?? 'N/A';
                    }
                    // Fallback to numeric mapping
                    return match ((string)$state) {
                        '1' => 'Kurang Mendukung',
                        '2' => 'Cukup Mendukung',
                        '3' => 'Mendukung',
                        '4' => 'Sangat Mendukung',
                        default => 'N/A'
                    };
                })
                ->color(function ($state, $record) {
                    if ($record->dataMahasiswa) {
                        return match ($record->dataMahasiswa->support_orang_tua) {
                            'Sangat Mendukung' => 'success',
                            'Mendukung' => 'info',
                            'Cukup Mendukung' => 'warning',
                            'Kurang Mendukung' => 'danger',
                            default => 'gray'
                        };
                    }
                    return 'gray';
                });
        } elseif (str_contains($kriteriaName, 'komitmen') || str_contains($kriteriaName, 'kuliah')) {
            // C5: Komitmen Kuliah - prioritize edited values in CalonMahasiswa over original DataMahasiswa
            return Tables\Columns\BadgeColumn::make($fieldName)
                ->label($label)
                ->formatStateUsing(function ($state, $record) {
                    // First, check if there's an edited value in CalonMahasiswa
                    if ($state !== null && $state !== '') {
                        // Convert numeric value to text using the model method
                        return $record->convertNumericToTextValue('c5', $state);
                    }
                    // Fallback to original DataMahasiswa value
                    if ($record->dataMahasiswa) {
                        return $record->dataMahasiswa->komitmen ?? 'N/A';
                    }
                    return 'N/A';
                })
                ->color(function ($state, $record) {
                    // Determine display text for color mapping
                    $displayText = '';
                    if ($state !== null && $state !== '') {
                        $displayText = $record->convertNumericToTextValue('c5', $state);
                    } elseif ($record->dataMahasiswa) {
                        $displayText = $record->dataMahasiswa->komitmen ?? '';
                    }

                    return match ($displayText) {
                        'Sangat Berkomitmen' => 'success',
                        'Berkomitmen' => 'info',
                        'Cukup Berkomitmen' => 'warning',
                        'Kurang Berkomitmen' => 'danger',
                        default => 'gray'
                    };
                });
        } elseif (str_contains($kriteriaName, 'aset') || str_contains($kriteriaName, 'ekonomi')) {
            // C6: Aset Keluarga/Kondisi Ekonomi - show raw value from DataMahasiswa
            return Tables\Columns\BadgeColumn::make($fieldName)
                ->label($label)
                ->formatStateUsing(function ($state, $record) {
                    // Always prioritize raw value from DataMahasiswa
                    if ($record->dataMahasiswa) {
                        return $record->dataMahasiswa->kondisi_ekonomi ?? 'N/A';
                    }
                    // Fallback to numeric mapping
                    return match ((string)$state) {
                        '1' => 'Sangat Sedikit',
                        '2' => 'Sedikit',
                        '3' => 'Cukup',
                        '4' => 'Banyak',
                        '5' => 'Sangat Banyak',
                        default => 'N/A'
                    };
                })
                ->color(function ($state, $record) {
                    // Determine display text for color mapping
                    $displayText = '';
                    if ($record->dataMahasiswa) {
                        $displayText = $record->dataMahasiswa->kondisi_ekonomi ?? '';
                    }

                    return match ($displayText) {
                        'Tidak Ada', 'Sangat Sedikit' => 'success', // High priority
                        'Sedikit' => 'info',
                        'Cukup' => 'warning',
                        'Banyak' => 'warning',
                        'Sangat Banyak' => 'danger', // Low priority
                        default => 'gray'
                    };
                });
        } elseif (str_contains($kriteriaName, 'kartu') || str_contains($kriteriaName, 'bantuan') || str_contains($kriteriaName, 'sosial')) {
            // C7: Kartu Bantuan Sosial - show raw value from DataMahasiswa
            return Tables\Columns\BadgeColumn::make($fieldName)
                ->label($label)
                ->formatStateUsing(function ($state, $record) {
                    // Always prioritize raw value from DataMahasiswa
                    if ($record->dataMahasiswa) {
                        return $record->dataMahasiswa->kip_status ?? 'N/A';
                    }
                    // Fallback to numeric mapping
                    return match ((string)$state) {
                        '1' => 'Tidak Ada',
                        '4' => 'KIP',
                        '5' => 'KIP + PKH',
                        default => 'N/A'
                    };
                })
                ->color(function ($state, $record) {
                    // Determine display text for color mapping
                    $displayText = '';
                    if ($record->dataMahasiswa) {
                        $displayText = $record->dataMahasiswa->kip_status ?? '';
                    }

                    return match ($displayText) {
                        'KIP + PKH', 'KIP + KKS', 'Lengkap KIP + PKH + KKS', 'KIP + PKH + KKS' => 'success', // High priority - multiple cards
                        'KIP', 'PKH', 'KKS' => 'info', // Medium priority - single card
                        'Tidak Ada', 'Tidak' => 'danger', // Low priority - no social aid card
                        default => 'gray'
                    };
                });
        } else {
            // Generic numeric column for other criteria
            return Tables\Columns\TextColumn::make($fieldName)
                ->label($label) // Use dynamic label from kriteria
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
