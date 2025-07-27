<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataMahasiswa extends Model
{
    protected $table = 'data_mahasiswa';

    protected $fillable = [
        'nama',
        'program_studi',
        'kip_status',
        'bukti_kip_status',
        'orang_tua_status',
        'bukti_orang_tua_status',
        'pekerjaan_orang_tua',
        'bukti_pekerjaan_orang_tua',
        'penghasilan_orang_tua',
        'bukti_penghasilan_orang_tua',
        'jumlah_saudara',
        'bukti_jumlah_saudara',
        'kepemilikan_rumah',
        'bukti_kepemilikan_rumah',
        'kondisi_rumah',
        'bukti_kondisi_rumah',
        'daya_listrik',
        'bukti_daya_listrik',
        'sumber_air',
        'bukti_sumber_air',
        'kendaraan',
        'bukti_kendaraan',
        'kondisi_ekonomi',
        'bukti_kondisi_ekonomi',
        'prestasi',
        'bukti_prestasi',
        'status_bekerja',
        'bukti_status_bekerja',
        'status_daftar_ulang',
        'bukti_status_daftar_ulang',
        'sumber_biaya_daftar_ulang',
        'bukti_sumber_biaya_daftar_ulang',
        'komitmen',
        'bukti_komitmen',
        'fleksibilitas_jurusan',
        'bukti_fleksibilitas_jurusan',
        'rencana_mendaftar_lagi',
        'bukti_rencana_mendaftar_lagi',
        'support_orang_tua',
        'bukti_support_orang_tua'
    ];

    protected $casts = [
        'penghasilan_orang_tua' => 'decimal:2',
        'jumlah_saudara' => 'integer',
        'daya_listrik' => 'integer'
    ];

    /**
     * Relasi ke CalonMahasiswa
     */
    public function calonMahasiswa(): HasMany
    {
        return $this->hasMany(CalonMahasiswa::class, 'data_mahasiswa_id');
    }

    /**
     * Get mapped value for specific criteria
     */
    public function getMappedValue(string $kriteriaKode): mixed
    {
        $mapping = $this->getCriteriaMapping();

        if (isset($mapping[$kriteriaKode])) {
            $column = $mapping[$kriteriaKode];
            return $this->{$column};
        }

        return null;
    }

    /**
     * Mapping antara kode kriteria dengan kolom data_mahasiswa
     */
    public static function getCriteriaMapping(): array
    {
        return [
            // Mapping berdasarkan kriteria baru
            'C1' => 'penghasilan_orang_tua',    // Penghasilan Orang Tua
            'C2' => 'kondisi_rumah',            // Kondisi Tempat Tinggal  
            'C3' => 'prestasi',                 // Prestasi
            'C4' => 'pekerjaan_orang_tua',      // Pekerjaan Orang Tua
            'C5' => 'komitmen',                 // Komitmen Kuliah
            'C6' => 'kondisi_ekonomi',          // Aset Keluarga
            'C7' => 'kip_status',               // Kartu Bantuan Sosial
        ];
    }

    /**
     * Get smart mapped value for criteria based on name patterns
     */
    public function getSmartMappedValueForKriteria(string $kriteriaName): mixed
    {
        $kriteriaName = strtolower($kriteriaName);

        // Smart mapping based on criteria name patterns
        if (str_contains($kriteriaName, 'penghasilan')) {
            return $this->penghasilan_orang_tua;
        } elseif (str_contains($kriteriaName, 'kondisi') || str_contains($kriteriaName, 'tempat tinggal')) {
            // Convert kondisi rumah to numeric scale
            return $this->convertToNumericValue($this->kondisi_rumah, 'kondisi_rumah');
        } elseif (str_contains($kriteriaName, 'prestasi')) {
            return $this->convertToNumericValue($this->prestasi, 'prestasi');
        } elseif (str_contains($kriteriaName, 'pekerjaan') || str_contains($kriteriaName, 'kerja')) {
            return $this->convertToNumericValue($this->pekerjaan_orang_tua, 'pekerjaan_orang_tua');
        } elseif (str_contains($kriteriaName, 'komitmen') || str_contains($kriteriaName, 'kuliah')) {
            return $this->convertToNumericValue($this->komitmen, 'komitmen');
        } elseif (str_contains($kriteriaName, 'aset') || str_contains($kriteriaName, 'ekonomi')) {
            return $this->convertToNumericValue($this->kondisi_ekonomi, 'aset_keluarga');
        } elseif (str_contains($kriteriaName, 'kartu') || str_contains($kriteriaName, 'bantuan')) {
            return $this->convertToNumericValue($this->kip_status, 'kartu_bantuan');
        } else {
            // Default fallback
            return rand(3, 5);
        }
    }
    /**
     * Convert various data types to numeric values for SPK
     * Considers Cost vs Benefit criteria for proper scoring
     */
    public function convertToNumericValue($value, string $context): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        $context = strtolower($context);

        // COST CRITERIA (nilai kecil = lebih baik/prioritas tinggi)
        if (str_contains($context, 'kondisi') || str_contains($context, 'tempat tinggal') || $context === 'kondisi_rumah') {
            // Cost: Kondisi buruk = nilai kecil = prioritas tinggi
            $kondisiMap = [
                'Sangat Kurang' => 1,  // Prioritas tertinggi
                'Kurang' => 2,
                'Cukup' => 3,
                'Baik' => 4,
                'Sangat Baik' => 5,    // Prioritas terendah
            ];
            return $kondisiMap[$value] ?? 3;
        }
        if (str_contains($context, 'pekerjaan') || str_contains($context, 'kerja') || $context === 'status_bekerja' || $context === 'pekerjaan_orang_tua') {
            // Cost: Tidak bekerja/pengangguran = butuh beasiswa = nilai kecil = prioritas tinggi
            $pekerjaanMap = [
                'Tidak Bekerja' => 1,
                'Pengangguran' => 1,
                'Buruh Harian' => 2,
                'Petani' => 2,
                'Pedagang Kecil' => 3,
                'Karyawan' => 4,
                'PNS' => 5,
                'Wiraswasta' => 4,
            ];
            return $pekerjaanMap[$value] ?? 2;
        }

        if (str_contains($context, 'aset') || str_contains($context, 'ekonomi') || $context === 'kondisi_ekonomi' || $context === 'aset_keluarga') {
            // Cost: Kondisi ekonomi buruk/aset sedikit = nilai kecil = prioritas tinggi
            $ekonomiMap = [
                'Tidak Ada' => 1,         // Prioritas tertinggi
                'Sangat Sedikit' => 1,    // Prioritas tertinggi
                'Sedikit' => 2,           // Prioritas tinggi
                'Cukup' => 3,             // Prioritas menengah
                'Banyak' => 4,            // Prioritas rendah
                'Sangat Banyak' => 5,     // Prioritas terendah
                // Legacy mappings for backward compatibility
                'Berhutang' => 1,         // Legacy: map to Sangat Sedikit
                'Defisit' => 1,           // Legacy: map to Sangat Sedikit
                'Sangat Kurang' => 1,     // Legacy: map to Sangat Sedikit
                'Kurang' => 2,            // Legacy: map to Sedikit
                'Surplus' => 4,           // Legacy: map to Banyak
                'Baik' => 4,              // Legacy: map to Banyak
                'Sangat Baik' => 5,       // Legacy: map to Sangat Banyak
            ];
            return $ekonomiMap[$value] ?? 2;
        }

        // BENEFIT CRITERIA (nilai besar = lebih baik)
        if (str_contains($context, 'prestasi') || $context === 'prestasi') {
            // Benefit: Prestasi tinggi = nilai besar = prioritas tinggi
            // Scale 0-100 for academic/non-academic achievements
            if (is_numeric($value)) {
                return (float) $value; // Direct numeric input
            }

            // Text-based prestasi conversion to 0-100 scale
            if (empty($value) || strtolower($value) === 'tidak ada prestasi khusus') return 0;
            if (str_contains(strtolower($value), 'juara 1') || str_contains(strtolower($value), 'nasional')) return 100;
            if (str_contains(strtolower($value), 'juara 2') || str_contains(strtolower($value), 'provinsi')) return 85;
            if (str_contains(strtolower($value), 'juara 3') || str_contains(strtolower($value), 'kabupaten')) return 75;
            if (str_contains(strtolower($value), 'juara') || str_contains(strtolower($value), 'lomba')) return 60;
            if (str_contains(strtolower($value), 'sertifikat') || str_contains(strtolower($value), 'pelatihan')) return 40;
            return 20; // Ada prestasi tapi tidak spesifik
        }

        if (str_contains($context, 'dukungan') || str_contains($context, 'support') || $context === 'support_orang_tua') {
            // Benefit: Dukungan tinggi = nilai besar = prioritas tinggi
            $supportMap = [
                'Kurang Mendukung' => 1,
                'Cukup Mendukung' => 2,
                'Mendukung' => 3,
                'Sangat Mendukung' => 4,
            ];
            return $supportMap[$value] ?? 2;
        }

        if (str_contains($context, 'komitmen') || $context === 'komitmen') {
            // Benefit: Komitmen tinggi = nilai besar = prioritas tinggi
            $komitmenMap = [
                'Kurang Berkomitmen' => 1,
                'Cukup Berkomitmen' => 2,
                'Berkomitmen' => 3,
                'Sangat Berkomitmen' => 4,
            ];
            return $komitmenMap[$value] ?? 2;
        }

        if (str_contains($context, 'kartu') || str_contains($context, 'bantuan') || $context === 'kip_status' || $context === 'kartu_bantuan') {
            // Benefit: Punya kartu bantuan = butuh beasiswa = nilai besar = prioritas tinggi
            $kartuBantuanMap = [
                'Tidak Ada' => 1,                    // Tidak punya kartu bantuan
                'Tidak' => 1,                        // Tidak punya kartu bantuan
                'KIP' => 4,                          // Punya KIP saja
                'PKH' => 4,                          // Punya PKH saja
                'KKS' => 4,                          // Punya KKS saja
                'KIP + PKH' => 5,                    // Punya KIP dan PKH
                'KIP + KKS' => 5,                    // Punya KIP dan KKS
                'PKH + KKS' => 5,                    // Punya PKH dan KKS
                'Lengkap KIP + PKH + KKS' => 5,      // Punya semua kartu bantuan
            ];

            return $kartuBantuanMap[$value] ?? 1; // Default tidak ada kartu
        }

        if (str_contains($context, 'ekonomi') || $context === 'kondisi_ekonomi') {
            // Cost: Kondisi ekonomi buruk = nilai kecil = prioritas tinggi
            $ekonomiMap = [
                'Tidak Ada' => 1,         // Prioritas tertinggi
                'Sangat Sedikit' => 1,    // Prioritas tertinggi
                'Berhutang' => 1,         // Prioritas tertinggi
                'Defisit' => 2,
                'Cukup' => 3,
                'Banyak' => 4,
                'Sangat Banyak' => 5,     // Prioritas terendah
                'Surplus' => 4,           // Prioritas terendah
            ];
            return $ekonomiMap[$value] ?? 2;
        }

        return 2.0; // Default neutral value
    }

    /**
     * Generate kode calon mahasiswa
     */
    public function generateKodeCalonMahasiswa(): string
    {
        return 'CM-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get display name for select options
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->nama . ' (' . $this->program_studi . ')';
    }

    /**
     * Transform data mahasiswa to calon mahasiswa format
     */
    public function toCalonMahasiswaData(): array
    {
        $data = [
            'data_mahasiswa_id' => $this->id,
            'nama' => $this->nama,
            'kode' => $this->generateKodeCalonMahasiswa()
        ];

        // Map values based on active criteria
        $kriteria = \App\Models\Kriteria::orderBy('kode')->get();
        $mapping = self::getCriteriaMapping();

        foreach ($kriteria as $k) {
            $columnName = strtolower($k->kode);

            if (isset($mapping[$k->kode])) {
                $sourceColumn = $mapping[$k->kode];
                $rawValue = $this->{$sourceColumn};
                $data[$columnName] = $this->convertToNumericValue($rawValue, $sourceColumn);
            } else {
                // Smart mapping fallback
                $data[$columnName] = $this->getSmartMappedValueForKriteria($k->nama);
            }
        }

        return $data;
    }

    /**
     * Get dropdown options for specific criteria based on Data Mahasiswa enum values
     */
    public static function getDropdownOptionsForKriteria(string $kriteriaName): array
    {
        $kriteriaName = strtolower($kriteriaName);

        if (str_contains($kriteriaName, 'kondisi') || str_contains($kriteriaName, 'tempat tinggal')) {
            return [
                'Sangat Kurang' => 'Sangat Kurang',
                'Kurang' => 'Kurang',
                'Cukup' => 'Cukup',
                'Baik' => 'Baik',
                'Sangat Baik' => 'Sangat Baik'
            ];
        }

        if (str_contains($kriteriaName, 'pekerjaan') || str_contains($kriteriaName, 'kerja')) {
            return [
                'Tidak Bekerja' => 'Tidak Bekerja',
                'Bekerja' => 'Bekerja'
            ];
        }

        if (str_contains($kriteriaName, 'dukungan') || str_contains($kriteriaName, 'support')) {
            return [
                'Kurang Mendukung' => 'Kurang Mendukung',
                'Cukup Mendukung' => 'Cukup Mendukung',
                'Mendukung' => 'Mendukung',
                'Sangat Mendukung' => 'Sangat Mendukung'
            ];
        }

        if (str_contains($kriteriaName, 'komitmen')) {
            return [
                'Cukup Berkomitmen' => 'Cukup Berkomitmen',
                'Berkomitmen' => 'Berkomitmen',
                'Sangat Berkomitmen' => 'Sangat Berkomitmen'
            ];
        }

        if (str_contains($kriteriaName, 'ekonomi')) {
            return [
                'Tidak Ada' => 'Tidak Ada',
                'Sangat Sedikit' => 'Sangat Sedikit',
                'Cukup' => 'Cukup',
                'Banyak' => 'Banyak',
                'Sangat Banyak' => 'Sangat Banyak'
            ];
        }

        if (str_contains($kriteriaName, 'kartu') || str_contains($kriteriaName, 'bantuan') || str_contains($kriteriaName, 'sosial')) {
            return [
                'Tidak Ada' => 'Tidak Ada',
                'KIP' => 'KIP',
                'PKH' => 'PKH',
                'KKS' => 'KKS',
                'KIP + PKH' => 'KIP + PKH',
                'KIP + KKS' => 'KIP + KKS',
                'PKH + KKS' => 'PKH + KKS',
                'Lengkap KIP + PKH + KKS' => 'Lengkap KIP + PKH + KKS'
            ];
        }

        // Return empty array for numeric fields (penghasilan, prestasi, etc)
        return [];
    }

    /**
     * Get raw mapped value (tidak di-convert) untuk auto-populate form
     */
    public function getRawMappedValueForKriteria(string $kriteriaName): mixed
    {
        $kriteriaName = strtolower($kriteriaName);

        // Return raw value (string) for dropdown fields
        if (str_contains($kriteriaName, 'kondisi') || str_contains($kriteriaName, 'tempat tinggal')) {
            return $this->kondisi_rumah;
        } elseif (str_contains($kriteriaName, 'pekerjaan') || str_contains($kriteriaName, 'kerja')) {
            return $this->pekerjaan_orang_tua;
        } elseif (str_contains($kriteriaName, 'dukungan') || str_contains($kriteriaName, 'support')) {
            return $this->support_orang_tua;
        } elseif (str_contains($kriteriaName, 'komitmen') || str_contains($kriteriaName, 'kuliah')) {
            return $this->komitmen;
        } elseif (str_contains($kriteriaName, 'aset') || str_contains($kriteriaName, 'ekonomi')) {
            return $this->kondisi_ekonomi;
        } elseif (str_contains($kriteriaName, 'kartu') || str_contains($kriteriaName, 'bantuan') || str_contains($kriteriaName, 'sosial')) {
            return $this->kip_status;
        } elseif (str_contains($kriteriaName, 'penghasilan')) {
            return $this->penghasilan_orang_tua;
        } elseif (str_contains($kriteriaName, 'prestasi')) {
            // For prestasi, return converted numeric value (0-100 scale)
            return $this->convertToNumericValue($this->prestasi, 'prestasi');
        } else {
            // Default numeric value
            return rand(3, 5);
        }
    }
}
