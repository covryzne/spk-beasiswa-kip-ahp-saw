<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DataMahasiswaResource\Pages;
use App\Filament\Admin\Resources\DataMahasiswaResource\RelationManagers;
use App\Models\DataMahasiswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class DataMahasiswaResource extends Resource
{
    protected static ?string $model = DataMahasiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Data Mahasiswa';
    protected static ?string $modelLabel = 'Data Mahasiswa';
    protected static ?string $pluralModelLabel = 'Data Mahasiswa';
    protected static ?string $navigationGroup = 'Setup Data';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Calon Mahasiswa'),
                        Forms\Components\TextInput::make('program_studi')
                            ->required()
                            ->maxLength(255)
                            ->label('Program Studi'),
                    ])->columns(2),

                Forms\Components\Section::make('Data Wawancara & Bukti Pendukung')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('kip_status')
                                    ->options([
                                        'KIP + PKH' => 'KIP + PKH',
                                        'Lengkap KIP + PKH + KKS' => 'Lengkap KIP + PKH + KKS',
                                        'KIP + KKS' => 'KIP + KKS',
                                        'PKH + KKS' => 'PKH + KKS',
                                        'KIP' => 'KIP',
                                        'PKH' => 'PKH',
                                        'KKS' => 'KKS',
                                        'Tidak Ada' => 'Tidak Ada',
                                        'Ya' => 'Ya (Lainnya)',
                                        'Tidak' => 'Tidak'
                                    ])
                                    ->required()
                                    ->label('Status KIP/DTKS/PKH/KKS/PPK')
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('bukti_kip_status')
                                    ->label('Bukti KIP/DTKS/PKH/KKS')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->directory('bukti-pendukung/kip-status')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->previewable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('orang_tua_status')
                                    ->options([
                                        'Masih Ada' => 'Masih Ada',
                                        'Ayah Meninggal' => 'Ayah Meninggal',
                                        'Ibu Meninggal' => 'Ibu Meninggal',
                                        'Keduanya Meninggal' => 'Keduanya Meninggal'
                                    ])
                                    ->required()
                                    ->label('Status Orang Tua')
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('bukti_orang_tua_status')
                                    ->label('Bukti Status Orang Tua')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->directory('bukti-pendukung/orang-tua-status')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->previewable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('pekerjaan_orang_tua')
                                    ->options([
                                        'Tidak Bekerja' => 'Tidak Bekerja',
                                        'Pengangguran' => 'Pengangguran',
                                        'Buruh Harian' => 'Buruh Harian',
                                        'Petani' => 'Petani',
                                        'Pedagang Kecil' => 'Pedagang Kecil',
                                        'Karyawan' => 'Karyawan',
                                        'Wiraswasta' => 'Wiraswasta',
                                        'PNS' => 'PNS',
                                        'Tukang Ojek' => 'Tukang Ojek',
                                        'Tukang Becak' => 'Tukang Becak',
                                        'Buruh Tani' => 'Buruh Tani',
                                        'Dirawat Nenek' => 'Dirawat Nenek'
                                    ])
                                    ->required()
                                    ->label('Pekerjaan Orang Tua')
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('bukti_pekerjaan_orang_tua')
                                    ->label('Bukti Pekerjaan Orang Tua')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->directory('bukti-pendukung/pekerjaan-orang-tua')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->previewable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('penghasilan_orang_tua')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->label('Penghasilan Orang Tua per Bulan')
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('bukti_penghasilan_orang_tua')
                                    ->label('Bukti Penghasilan')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->directory('bukti-pendukung/penghasilan-orang-tua')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->previewable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('jumlah_saudara')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->label('Jumlah Saudara Kandung')
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('bukti_jumlah_saudara')
                                    ->label('Bukti Jumlah Saudara')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->directory('bukti-pendukung/jumlah-saudara')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->previewable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('kepemilikan_rumah')
                                    ->options([
                                        'Milik Sendiri' => 'Milik Sendiri',
                                        'Sewa' => 'Sewa',
                                        'Kontrak' => 'Kontrak',
                                        'Menumpang' => 'Menumpang',
                                        'Lainnya' => 'Lainnya'
                                    ])
                                    ->required()
                                    ->label('Status Kepemilikan Rumah')
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('bukti_kepemilikan_rumah')
                                    ->label('Bukti Kepemilikan Rumah')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->directory('bukti-pendukung/kepemilikan-rumah')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->previewable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('kondisi_rumah')
                                    ->options([
                                        'Sangat Baik' => 'Sangat Baik',
                                        'Baik' => 'Baik',
                                        'Cukup' => 'Cukup',
                                        'Kurang' => 'Kurang',
                                        'Sangat Kurang' => 'Sangat Kurang'
                                    ])
                                    ->required()
                                    ->label('Kondisi Fisik Rumah')
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('bukti_kondisi_rumah')
                                    ->label('Bukti Kondisi Rumah')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->directory('bukti-pendukung/kondisi-rumah')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->previewable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('daya_listrik')
                                    ->required()
                                    ->numeric()
                                    ->suffix('Watt')
                                    ->label('Daya Listrik')
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('bukti_daya_listrik')
                                    ->label('Bukti Daya Listrik')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->directory('bukti-pendukung/daya-listrik')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->previewable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('sumber_air')
                                    ->options([
                                        'PDAM' => 'PDAM',
                                        'PAM' => 'PAM',
                                        'Sumur Bor' => 'Sumur Bor',
                                        'Sumur Gali' => 'Sumur Gali',
                                        'Air Hujan' => 'Air Hujan',
                                        'Sungai/Mata Air' => 'Sungai/Mata Air'
                                    ])
                                    ->required()
                                    ->label('Sumber Air')
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('bukti_sumber_air')
                                    ->label('Bukti Sumber Air')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->directory('bukti-pendukung/sumber-air')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->previewable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('kendaraan')
                                    ->options([
                                        'Motor' => 'Motor',
                                        'Sepeda Motor' => 'Sepeda Motor',
                                        'Mobil' => 'Mobil',
                                        'Sepeda' => 'Sepeda',
                                        'Tidak Ada' => 'Tidak Ada'
                                    ])
                                    ->required()
                                    ->label('Kendaraan')
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('bukti_kendaraan')
                                    ->label('Bukti Kendaraan')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->directory('bukti-pendukung/kendaraan')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->previewable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('kondisi_ekonomi')
                                    ->options([
                                        'Tidak Ada' => 'Tidak Ada',
                                        'Sangat Sedikit' => 'Sangat Sedikit',
                                        'Sedikit' => 'Sedikit',
                                        'Cukup' => 'Cukup',
                                        'Banyak' => 'Banyak',
                                        'Sangat Banyak' => 'Sangat Banyak',
                                        'Surplus' => 'Surplus',
                                        'Defisit' => 'Defisit',
                                        'Berhutang' => 'Berhutang'
                                    ])
                                    ->required()
                                    ->label('Kondisi Ekonomi/Aset Keluarga')
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('bukti_kondisi_ekonomi')
                                    ->label('Bukti Kondisi Ekonomi')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->directory('bukti-pendukung/kondisi-ekonomi')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->previewable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Textarea::make('prestasi')
                                    ->maxLength(1000)
                                    ->label('Prestasi yang Pernah Diraih')
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('bukti_prestasi')
                                    ->label('Bukti Prestasi')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->directory('bukti-pendukung/prestasi')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->previewable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('status_bekerja')
                                    ->options([
                                        'Bekerja' => 'Bekerja',
                                        'Tidak Bekerja' => 'Tidak Bekerja'
                                    ])
                                    ->required()
                                    ->label('Status Pekerjaan Saat Ini')
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('bukti_status_bekerja')
                                    ->label('Bukti Status Bekerja')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->directory('bukti-pendukung/status-bekerja')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->previewable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('status_daftar_ulang')
                                    ->options([
                                        'Sudah' => 'Sudah',
                                        'Belum' => 'Belum'
                                    ])
                                    ->required()
                                    ->label('Status Daftar Ulang')
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('bukti_status_daftar_ulang')
                                    ->label('Bukti Status Daftar Ulang')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->directory('bukti-pendukung/status-daftar-ulang')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->previewable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Textarea::make('sumber_biaya_daftar_ulang')
                                    ->maxLength(500)
                                    ->label('Sumber Biaya Daftar Ulang')
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('bukti_sumber_biaya_daftar_ulang')
                                    ->label('Bukti Sumber Biaya')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->directory('bukti-pendukung/sumber-biaya-daftar-ulang')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->previewable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('komitmen')
                                    ->options([
                                        'Sangat Berkomitmen' => 'Sangat Berkomitmen',
                                        'Berkomitmen' => 'Berkomitmen',
                                        'Cukup Berkomitmen' => 'Cukup Berkomitmen',
                                        'Kurang Berkomitmen' => 'Kurang Berkomitmen'
                                    ])
                                    ->required()
                                    ->label('Tingkat Komitmen')
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('bukti_komitmen')
                                    ->label('Bukti Komitmen')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->directory('bukti-pendukung/komitmen')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->previewable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('fleksibilitas_jurusan')
                                    ->options([
                                        'Ya' => 'Ya',
                                        'Tidak' => 'Tidak'
                                    ])
                                    ->required()
                                    ->label('Bersedia di Jurusan Lain')
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('bukti_fleksibilitas_jurusan')
                                    ->label('Bukti Fleksibilitas')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->directory('bukti-pendukung/fleksibilitas-jurusan')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->previewable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('rencana_mendaftar_lagi')
                                    ->options([
                                        'Ya' => 'Ya',
                                        'Tidak' => 'Tidak'
                                    ])
                                    ->required()
                                    ->label('Rencana Mendaftar Lagi Tahun Depan')
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('bukti_rencana_mendaftar_lagi')
                                    ->label('Bukti Rencana')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->directory('bukti-pendukung/rencana-mendaftar-lagi')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->previewable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('support_orang_tua')
                                    ->options([
                                        'Sangat Mendukung' => 'Sangat Mendukung',
                                        'Mendukung' => 'Mendukung',
                                        'Cukup Mendukung' => 'Cukup Mendukung',
                                        'Kurang Mendukung' => 'Kurang Mendukung'
                                    ])
                                    ->required()
                                    ->label('Tingkat Dukungan Orang Tua')
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('bukti_support_orang_tua')
                                    ->label('Bukti Dukungan Orang Tua')
                                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                                    ->directory('bukti-pendukung/support-orang-tua')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->previewable()
                                    ->columnSpan(1),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable()
                    ->label('Nama'),
                Tables\Columns\TextColumn::make('program_studi')
                    ->searchable()
                    ->sortable()
                    ->label('Program Studi'),
                Tables\Columns\BadgeColumn::make('kip_status')
                    ->colors([
                        'success' => 'Ya',
                        'gray' => 'Tidak',
                    ])
                    ->label('Status KIP'),
                Tables\Columns\TextColumn::make('penghasilan_orang_tua')
                    ->sortable()
                    ->formatStateUsing(fn($state): string => $state ? 'Rp ' . number_format($state, 0, ',', '.') : 'N/A')
                    ->color(fn($state): string => $state <= 2000000 ? 'success' : ($state <= 5000000 ? 'warning' : 'danger'))
                    ->label('Penghasilan'),
                Tables\Columns\BadgeColumn::make('kondisi_rumah')
                    ->colors([
                        'success' => 'Sangat Kurang',  // Prioritas tinggi untuk beasiswa (hijau)
                        'info' => 'Kurang',            // Biru
                        'warning' => 'Cukup',          // Kuning
                        'danger' => 'Baik',            // Merah untuk test visibility
                        'gray' => 'Sangat Baik',       // Abu-abu
                    ])
                    ->label('Kondisi Rumah'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Dibuat'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kip_status')
                    ->options([
                        'Ya' => 'Ya',
                        'Tidak' => 'Tidak'
                    ])
                    ->label('Status KIP'),
                Tables\Filters\SelectFilter::make('kondisi_rumah')
                    ->options([
                        'Sangat Baik' => 'Sangat Baik',
                        'Baik' => 'Baik',
                        'Cukup' => 'Cukup',
                        'Kurang' => 'Kurang',
                        'Sangat Kurang' => 'Sangat Kurang'
                    ])
                    ->label('Kondisi Rumah'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('print_pdf')
                    ->label('Print PDF')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn(DataMahasiswa $record): string => route('data-mahasiswa.pdf', $record))
                    ->openUrlInNewTab(),
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
                    ->url(fn(): string => route('data-mahasiswa.print-all'))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\CalonMahasiswaRelationManager::class, // Comment out for now
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDataMahasiswas::route('/'),
            'create' => Pages\CreateDataMahasiswa::route('/create'),
            'edit' => Pages\EditDataMahasiswa::route('/{record}/edit'),
            // 'view' => Pages\ViewDataMahasiswa::route('/{record}'), // Comment out for now
        ];
    }
}
