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
        'orang_tua_status',
        'pekerjaan_orang_tua',
        'penghasilan_orang_tua',
        'jumlah_saudara',
        'kepemilikan_rumah',
        'kondisi_rumah',
        'daya_listrik',
        'sumber_air',
        'kendaraan',
        'kondisi_ekonomi',
        'prestasi',
        'status_bekerja',
        'status_daftar_ulang',
        'sumber_biaya_daftar_ulang',
        'komitmen',
        'fleksibilitas_jurusan',
        'rencana_mendaftar_lagi',
        'support_orang_tua'
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
            // Mapping default - bisa disesuaikan dengan kriteria yang ada
            'C1' => 'penghasilan_orang_tua',    // Penghasilan Orang Tua
            'C2' => 'kondisi_rumah',            // Kondisi Tempat Tinggal  
            'C3' => 'prestasi',                 // Prestasi
            'C4' => 'status_bekerja',           // Status Pekerjaan
            'C5' => 'support_orang_tua',        // Dukungan Orang Tua
            // Tambah mapping lain sesuai kebutuhan
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
            return $this->convertToNumericValue($this->status_bekerja, 'status_bekerja');
        } elseif (str_contains($kriteriaName, 'dukungan') || str_contains($kriteriaName, 'support')) {
            return $this->convertToNumericValue($this->support_orang_tua, 'support_orang_tua');
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
        if (str_contains($context, 'pekerjaan') || str_contains($context, 'kerja') || $context === 'status_bekerja') {
            // Cost: Tidak bekerja = butuh beasiswa = nilai kecil = prioritas tinggi
            // Binary options: 1 dan 2 (avoid 0 for SAW calculation)
            return $value === 'Tidak Bekerja' ? 1 : 2;
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
                'Cukup Berkomitmen' => 1,
                'Berkomitmen' => 2,
                'Sangat Berkomitmen' => 3,
            ];
            return $komitmenMap[$value] ?? 2;
        }

        if (str_contains($context, 'ekonomi') || $context === 'kondisi_ekonomi') {
            // Cost: Kondisi ekonomi buruk = nilai kecil = prioritas tinggi
            $ekonomiMap = [
                'Berhutang' => 1,    // Prioritas tertinggi
                'Defisit' => 2,
                'Cukup' => 3,
                'Surplus' => 4,      // Prioritas terendah
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
                'Berhutang' => 'Berhutang',
                'Defisit' => 'Defisit',
                'Cukup' => 'Cukup',
                'Surplus' => 'Surplus'
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
            return $this->status_bekerja;
        } elseif (str_contains($kriteriaName, 'dukungan') || str_contains($kriteriaName, 'support')) {
            return $this->support_orang_tua;
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
