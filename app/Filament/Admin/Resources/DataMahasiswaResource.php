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

                Forms\Components\Section::make('Data Wawancara')
                    ->schema([
                        Forms\Components\Select::make('kip_status')
                            ->options([
                                'Ya' => 'Ya (Memiliki KIP/DTKS/PKH/KKS/PPK/Panti)',
                                'Tidak' => 'Tidak'
                            ])
                            ->required()
                            ->label('Status KIP/DTKS/PKH/KKS/PPK'),

                        Forms\Components\Select::make('orang_tua_status')
                            ->options([
                                'Masih Ada' => 'Masih Ada',
                                'Ayah Meninggal' => 'Ayah Meninggal',
                                'Ibu Meninggal' => 'Ibu Meninggal',
                                'Keduanya Meninggal' => 'Keduanya Meninggal'
                            ])
                            ->required()
                            ->label('Status Orang Tua'),

                        Forms\Components\TextInput::make('pekerjaan_orang_tua')
                            ->required()
                            ->maxLength(255)
                            ->label('Pekerjaan Orang Tua'),

                        Forms\Components\TextInput::make('penghasilan_orang_tua')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->label('Penghasilan Orang Tua per Bulan'),

                        Forms\Components\TextInput::make('jumlah_saudara')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->label('Jumlah Saudara Kandung'),

                        Forms\Components\Select::make('kepemilikan_rumah')
                            ->options([
                                'Milik Sendiri' => 'Milik Sendiri',
                                'Sewa' => 'Sewa',
                                'Kontrak' => 'Kontrak',
                                'Menumpang' => 'Menumpang',
                                'Lainnya' => 'Lainnya'
                            ])
                            ->required()
                            ->label('Status Kepemilikan Rumah'),

                        Forms\Components\Select::make('kondisi_rumah')
                            ->options([
                                'Sangat Baik' => 'Sangat Baik',
                                'Baik' => 'Baik',
                                'Cukup' => 'Cukup',
                                'Kurang' => 'Kurang',
                                'Sangat Kurang' => 'Sangat Kurang'
                            ])
                            ->required()
                            ->label('Kondisi Fisik Rumah'),

                        Forms\Components\TextInput::make('daya_listrik')
                            ->required()
                            ->numeric()
                            ->suffix('Watt')
                            ->label('Daya Listrik'),

                        Forms\Components\Select::make('sumber_air')
                            ->options([
                                'PDAM' => 'PDAM',
                                'Sumur Bor' => 'Sumur Bor',
                                'Sumur Gali' => 'Sumur Gali',
                                'Air Hujan' => 'Air Hujan',
                                'Sungai/Mata Air' => 'Sungai/Mata Air'
                            ])
                            ->required()
                            ->label('Sumber Air'),

                        Forms\Components\Select::make('kendaraan')
                            ->options([
                                'Motor' => 'Motor',
                                'Mobil' => 'Mobil',
                                'Sepeda' => 'Sepeda',
                                'Tidak Ada' => 'Tidak Ada'
                            ])
                            ->required()
                            ->label('Kendaraan'),

                        Forms\Components\Select::make('kondisi_ekonomi')
                            ->options([
                                'Surplus' => 'Surplus',
                                'Cukup' => 'Cukup',
                                'Defisit' => 'Defisit',
                                'Berhutang' => 'Berhutang'
                            ])
                            ->required()
                            ->label('Kondisi Ekonomi'),

                        Forms\Components\Textarea::make('prestasi')
                            ->maxLength(1000)
                            ->label('Prestasi yang Pernah Diraih'),

                        Forms\Components\Select::make('status_bekerja')
                            ->options([
                                'Bekerja' => 'Bekerja',
                                'Tidak Bekerja' => 'Tidak Bekerja'
                            ])
                            ->required()
                            ->label('Status Pekerjaan Saat Ini'),

                        Forms\Components\Select::make('status_daftar_ulang')
                            ->options([
                                'Sudah' => 'Sudah',
                                'Belum' => 'Belum'
                            ])
                            ->required()
                            ->label('Status Daftar Ulang'),

                        Forms\Components\Textarea::make('sumber_biaya_daftar_ulang')
                            ->maxLength(500)
                            ->label('Sumber Biaya Daftar Ulang'),

                        Forms\Components\Select::make('komitmen')
                            ->options([
                                'Sangat Berkomitmen' => 'Sangat Berkomitmen',
                                'Berkomitmen' => 'Berkomitmen',
                                'Cukup Berkomitmen' => 'Cukup Berkomitmen'
                            ])
                            ->required()
                            ->label('Tingkat Komitmen'),

                        Forms\Components\Select::make('fleksibilitas_jurusan')
                            ->options([
                                'Ya' => 'Ya',
                                'Tidak' => 'Tidak'
                            ])
                            ->required()
                            ->label('Bersedia di Jurusan Lain'),

                        Forms\Components\Select::make('rencana_mendaftar_lagi')
                            ->options([
                                'Ya' => 'Ya',
                                'Tidak' => 'Tidak'
                            ])
                            ->required()
                            ->label('Rencana Mendaftar Lagi Tahun Depan'),

                        Forms\Components\Select::make('support_orang_tua')
                            ->options([
                                'Sangat Mendukung' => 'Sangat Mendukung',
                                'Mendukung' => 'Mendukung',
                                'Cukup Mendukung' => 'Cukup Mendukung',
                                'Kurang Mendukung' => 'Kurang Mendukung'
                            ])
                            ->required()
                            ->label('Tingkat Dukungan Orang Tua'),
                    ])->columns(2)
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
                Tables\Columns\TextColumn::make('calonMahasiswa')
                    ->badge()
                    ->getStateUsing(fn(DataMahasiswa $record) => $record->calonMahasiswa->count())
                    ->colors(['success'])
                    ->label('Used in SPK'),
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
