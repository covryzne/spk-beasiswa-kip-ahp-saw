<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CalonMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'calon_mahasiswa';

    protected $fillable = [
        'kode',
        'nama',
        'catatan',
        'data_mahasiswa_id', // Relasi ke DataMahasiswa
    ];

    /**
     * Get dynamic fillable fields including criteria columns
     */
    public function getFillable()
    {
        $baseFillable = ['kode', 'nama', 'catatan', 'data_mahasiswa_id'];

        // Add criteria columns dynamically
        $criteriaColumns = $this->getCriteriaColumns();

        return array_merge($baseFillable, $criteriaColumns);
    }

    /**
     * Relasi ke DataMahasiswa
     */
    public function dataMahasiswa()
    {
        return $this->belongsTo(DataMahasiswa::class, 'data_mahasiswa_id');
    }

    /**
     * Auto-populate data dari DataMahasiswa berdasarkan kriteria aktif
     */
    public function populateFromDataMahasiswa(): void
    {
        if (!$this->dataMahasiswa) {
            return;
        }

        // Get active criteria
        $kriteria = Kriteria::orderBy('kode')->get();

        foreach ($kriteria as $k) {
            $column = strtolower($k->kode);

            // Smart mapping dari DataMahasiswa
            $value = $this->dataMahasiswa->getSmartMappedValueForKriteria($k->nama);

            if ($value !== null) {
                // Convert to numeric value if needed for SPK calculation
                if ($k->jenis === 'Cost' || $k->jenis === 'Benefit') {
                    $value = $this->dataMahasiswa->getNumericValueForSPK($this->findDataMahasiswaColumn($k->nama));
                }

                $this->{$column} = $value;
            }
        }

        // Auto-generate nama and kode if not set
        if (!$this->nama && $this->dataMahasiswa) {
            $this->nama = $this->dataMahasiswa->nama;
        }

        if (!$this->kode && $this->dataMahasiswa) {
            $this->kode = $this->dataMahasiswa->generateKodeCalonMahasiswa();
        }
    }

    /**
     * Find corresponding DataMahasiswa column for criteria name
     */
    private function findDataMahasiswaColumn($kriteriaNama): ?string
    {
        $kriteriaNama = strtolower($kriteriaNama);

        // Mapping logic similar to DataMahasiswa
        if (str_contains($kriteriaNama, 'penghasilan')) {
            return 'penghasilan_orang_tua';
        }

        if (str_contains($kriteriaNama, 'kondisi') || str_contains($kriteriaNama, 'tempat tinggal')) {
            return 'kondisi_rumah';
        }

        if (str_contains($kriteriaNama, 'prestasi')) {
            return 'prestasi';
        }

        if (str_contains($kriteriaNama, 'support') || str_contains($kriteriaNama, 'dukungan')) {
            return 'support_orang_tua';
        }

        if (str_contains($kriteriaNama, 'komitmen')) {
            return 'komitmen';
        }

        return null;
    }

    /**
     * Get criteria columns from database
     */
    public function getCriteriaColumns(): array
    {
        try {
            return Kriteria::pluck('kode')->map(fn($code) => strtolower($code))->toArray();
        } catch (\Exception $e) {
            // Fallback if Kriteria table doesn't exist or is empty
            return ['c1', 'c2', 'c3', 'c4', 'c5'];
        }
    }

    /**
     * Get dynamic casts including criteria columns
     */
    public function getCasts()
    {
        $baseCasts = parent::getCasts();

        // Add criteria columns casts dynamically based on criteria type
        try {
            $kriterias = Kriteria::all();
            foreach ($kriterias as $kriteria) {
                $column = strtolower($kriteria->kode);
                $kriteriaName = strtolower($kriteria->nama);

                // Determine appropriate cast based on criteria name
                if (str_contains($kriteriaName, 'penghasilan') || str_contains($kriteriaName, 'gaji') || str_contains($kriteriaName, 'income')) {
                    $baseCasts[$column] = 'integer'; // No decimals for currency
                } elseif (str_contains($kriteriaName, 'tempat tinggal') || str_contains($kriteriaName, 'lokasi') || str_contains($kriteriaName, 'kondisi')) {
                    $baseCasts[$column] = 'integer'; // Integer for scale ratings
                } elseif (str_contains($kriteriaName, 'usia') || str_contains($kriteriaName, 'umur') || str_contains($kriteriaName, 'age')) {
                    $baseCasts[$column] = 'integer'; // Integer for age
                } else {
                    $baseCasts[$column] = 'decimal:1'; // Minimal decimals for scores
                }
            }
        } catch (\Exception $e) {
            // Fallback casting
            $criteriaColumns = $this->getCriteriaColumns();
            foreach ($criteriaColumns as $column) {
                $baseCasts[$column] = 'decimal:1';
            }
        }

        return $baseCasts;
    }

    /**
     * Get the hasil seleksi for this calon mahasiswa
     */
    public function hasilSeleksi(): HasOne
    {
        return $this->hasOne(HasilSeleksi::class);
    }

    /**
     * Get tempat tinggal options
     */
    public static function getTempatTinggalOptions(): array
    {
        return [
            1 => '1 - Ngontrak/Kost',
            2 => '2 - Rumah Orangtua/Saudara',
            3 => '3 - Rumah Dinas',
            4 => '4 - Rumah Kredit',
            5 => '5 - Rumah Sendiri',
        ];
    }

    /**
     * Get criteria label/formatted value by criteria code
     */
    public function getCriteriaLabel(string $criteriaCode): string
    {
        $column = strtolower($criteriaCode);
        $value = $this->getAttribute($column);

        if ($value === null) {
            return 'Tidak diketahui';
        }

        try {
            $kriteria = Kriteria::where('kode', strtoupper($criteriaCode))->first();
            if (!$kriteria) {
                return (string) $value;
            }

            $kriteriaName = strtolower($kriteria->nama);

            // Format berdasarkan jenis kriteria
            if (str_contains($kriteriaName, 'penghasilan') || str_contains($kriteriaName, 'gaji') || str_contains($kriteriaName, 'income')) {
                return 'Rp ' . number_format($value, 0, ',', '.');
            } elseif (str_contains($kriteriaName, 'tempat tinggal') || str_contains($kriteriaName, 'lokasi') || str_contains($kriteriaName, 'kondisi')) {
                return $this->getLocationLabel($value);
            } elseif (str_contains($kriteriaName, 'usia') || str_contains($kriteriaName, 'umur') || str_contains($kriteriaName, 'age')) {
                return $value . ' tahun';
            } elseif (str_contains($kriteriaName, 'jarak') || str_contains($kriteriaName, 'distance')) {
                return number_format($value, 1) . ' km';
            } else {
                // Academic scores, achievements, etc.
                return number_format($value, 1);
            }
        } catch (\Exception $e) {
            return (string) $value;
        }
    }

    /**
     * Get location label based on value
     */
    private function getLocationLabel($value): string
    {
        $options = [
            1 => '1 - Ngontrak/Kost',
            2 => '2 - Rumah Orangtua/Saudara',
            3 => '3 - Rumah Dinas',
            4 => '4 - Rumah Kredit',
            5 => '5 - Rumah Sendiri',
        ];

        return $options[$value] ?? 'Tidak diketahui';
    }

    /**
     * Get tempat tinggal label (backward compatibility)
     */
    public function getTempatTinggalLabelAttribute(): string
    {
        // Try to find location criteria dynamically
        $locationCriteria = Kriteria::where('nama', 'like', '%tempat tinggal%')
            ->orWhere('nama', 'like', '%lokasi%')
            ->orWhere('nama', 'like', '%kondisi%')
            ->first();

        if ($locationCriteria) {
            return $this->getCriteriaLabel($locationCriteria->kode);
        }

        // Fallback to c2
        return $this->getLocationLabel($this->c2 ?? null);
    }

    /**
     * Format penghasilan as currency (backward compatibility)
     */
    public function getPenghasilanFormattedAttribute(): string
    {
        // Try to find income criteria dynamically
        $incomeCriteria = Kriteria::where('nama', 'like', '%penghasilan%')
            ->orWhere('nama', 'like', '%gaji%')
            ->orWhere('nama', 'like', '%income%')
            ->first();

        if ($incomeCriteria) {
            return $this->getCriteriaLabel($incomeCriteria->kode);
        }

        // Fallback to c1
        $value = $this->c1 ?? 0;
        return 'Rp ' . number_format($value, 0, ',', '.');
    }

    /**
     * Get all criteria values as array - dynamic version
     */
    public function getCriteriaValues(): array
    {
        $values = [];
        $criteriaColumns = $this->getCriteriaColumns();

        foreach ($criteriaColumns as $column) {
            $values[$column] = $this->getAttribute($column);
        }

        return $values;
    }

    /**
     * Get criteria value by criteria code
     */
    public function getCriteriaValue(string $criteriaCode): mixed
    {
        $column = strtolower($criteriaCode);
        return $this->getAttribute($column);
    }

    /**
     * Set criteria value by criteria code
     */
    public function setCriteriaValue(string $criteriaCode, mixed $value): void
    {
        $column = strtolower($criteriaCode);
        $this->setAttribute($column, $value);
    }

    /**
     * Scope to get ordered calon mahasiswa by kode
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('kode');
    }

    /**
     * Scope untuk natural sorting berdasarkan kode
     * Akan mengurutkan A1, A2, A10, A11 dengan benar (bukan A1, A10, A11, A2)
     */
    public function scopeOrderByKodeNatural($query)
    {
        return $query->orderByRaw('LENGTH(kode), kode');
    }

    /**
     * Boot method untuk handle saving dengan konversi dropdown ke numeric
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->convertDropdownValuesToNumeric();
        });
    }

    /**
     * Convert dropdown values to numeric for SPK calculation
     */
    private function convertDropdownValuesToNumeric(): void
    {
        $kriteria = Kriteria::orderBy('kode')->get();

        foreach ($kriteria as $k) {
            $columnName = strtolower($k->kode);
            $value = $this->{$columnName};

            if ($value !== null) {
                // Get dropdown options for this criteria
                $dropdownOptions = $k->getDropdownOptions();

                // If this criteria uses dropdown and value is not numeric
                if (!empty($dropdownOptions) && !is_numeric($value)) {
                    // Convert using DataMahasiswa converter
                    $numericValue = app(DataMahasiswa::class)->convertToNumericValue($value, $k->nama);
                    $this->{$columnName} = $numericValue;
                }
            }
        }
    }

    /**
     * Convert single dropdown value to numeric based on criteria name
     */
    private function convertDropdownValueToNumeric($value, string $kriteriaName): float
    {
        $kriteriaName = strtolower($kriteriaName);

        if (str_contains($kriteriaName, 'kondisi') || str_contains($kriteriaName, 'tempat tinggal')) {
            $kondisiMap = [
                'Sangat Kurang' => 1,
                'Kurang' => 2,
                'Cukup' => 3,
                'Baik' => 4,
                'Sangat Baik' => 5,
            ];
            return $kondisiMap[$value] ?? 3;
        }

        if (str_contains($kriteriaName, 'pekerjaan') || str_contains($kriteriaName, 'kerja')) {
            return $value === 'Tidak Bekerja' ? 5 : 2; // Higher score for not working
        }

        if (str_contains($kriteriaName, 'dukungan') || str_contains($kriteriaName, 'support')) {
            $supportMap = [
                'Kurang Mendukung' => 1,
                'Cukup Mendukung' => 2,
                'Mendukung' => 3,
                'Sangat Mendukung' => 4,
            ];
            return $supportMap[$value] ?? 3;
        }

        // Default return as is if already numeric or unknown type
        return is_numeric($value) ? (float) $value : 3.0;
    }

    /**
     * Convert dropdown values to numeric for SPK calculation
     * This will be called when saving data to ensure numeric values for calculation
     */
    protected static function booted()
    {
        static::saving(function ($model) {
            $kriteria = \App\Models\Kriteria::orderBy('kode')->get();
            $dataMahasiswa = new \App\Models\DataMahasiswa();

            foreach ($kriteria as $k) {
                $fieldName = strtolower($k->kode);
                $rawValue = $model->{$fieldName};

                // Convert dropdown values to numeric based on criteria type
                if ($rawValue !== null && !is_numeric($rawValue)) {
                    // Get converted numeric value
                    $numericValue = $dataMahasiswa->convertToNumericValue($rawValue, $k->nama);
                    $model->{$fieldName} = $numericValue;
                }
            }
        });
    }

    /**
     * Get raw string value from numeric value for form display
     */
    public function getRawValueForKriteria(string $kriteriaName): mixed
    {
        $kriteriaName = strtolower($kriteriaName);
        $kriteria = \App\Models\Kriteria::where('nama', 'like', '%' . $kriteriaName . '%')->first();

        if (!$kriteria) {
            return null;
        }

        $fieldName = strtolower($kriteria->kode);
        $numericValue = $this->{$fieldName};

        // If we have DataMahasiswa, use raw value from there (preferred)
        if ($this->dataMahasiswa) {
            return $this->dataMahasiswa->getRawMappedValueForKriteria($kriteriaName);
        }

        // Otherwise, reverse map from numeric value to string
        return $this->reverseMapNumericToString($kriteriaName, $numericValue);
    }

    /**
     * Reverse map numeric value back to string for dropdown
     */
    private function reverseMapNumericToString(string $kriteriaName, $numericValue): mixed
    {
        $kriteriaName = strtolower($kriteriaName);

        if (str_contains($kriteriaName, 'kondisi') || str_contains($kriteriaName, 'tempat tinggal')) {
            $kondisiMap = [
                1 => 'Sangat Kurang',
                2 => 'Kurang',
                3 => 'Cukup',
                4 => 'Baik',
                5 => 'Sangat Baik'
            ];
            return $kondisiMap[$numericValue] ?? 'Cukup';
        }

        if (str_contains($kriteriaName, 'pekerjaan') || str_contains($kriteriaName, 'kerja')) {
            return $numericValue == 1 ? 'Tidak Bekerja' : 'Bekerja';
        }

        if (str_contains($kriteriaName, 'dukungan') || str_contains($kriteriaName, 'support')) {
            $supportMap = [
                1 => 'Kurang Mendukung',
                2 => 'Cukup Mendukung',
                3 => 'Mendukung',
                4 => 'Sangat Mendukung'
            ];
            return $supportMap[$numericValue] ?? 'Cukup Mendukung';
        }

        // For numeric fields (penghasilan, prestasi), return as is
        return $numericValue;
    }
}
