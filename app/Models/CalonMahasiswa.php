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
    ];

    /**
     * Get dynamic fillable fields including criteria columns
     */
    public function getFillable()
    {
        $baseFillable = ['kode', 'nama', 'catatan'];

        // Add criteria columns dynamically
        $criteriaColumns = $this->getCriteriaColumns();

        return array_merge($baseFillable, $criteriaColumns);
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
}
