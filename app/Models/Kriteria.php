<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = [
        'kode',
        'nama',
        'jenis',
        'bobot',
        'deskripsi',
    ];

    protected $casts = [
        'bobot' => 'decimal:12',
        'jenis' => 'string',
    ];

    /**
     * Get the matriks AHP records where this criteria is the first criteria
     */
    public function matriksAhpAsFirst(): HasMany
    {
        return $this->hasMany(MatriksAhp::class, 'kriteria_1_id');
    }

    /**
     * Get the matriks AHP records where this criteria is the second criteria
     */
    public function matriksAhpAsSecond(): HasMany
    {
        return $this->hasMany(MatriksAhp::class, 'kriteria_2_id');
    }

    /**
     * Check if criteria is Cost type
     */
    public function isCost(): bool
    {
        return $this->jenis === 'Cost';
    }

    /**
     * Check if criteria is Benefit type
     */
    public function isBenefit(): bool
    {
        return $this->jenis === 'Benefit';
    }

    /**
     * Get criteria options for forms
     */
    public static function getJenisOptions(): array
    {
        return [
            'Cost' => 'Cost (Semakin kecil semakin baik)',
            'Benefit' => 'Benefit (Semakin besar semakin baik)',
        ];
    }

    /**
     * Scope to get ordered criteria by kode
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('kode');
    }

    /**
     * Scope untuk natural sorting berdasarkan kode
     * Akan mengurutkan C1, C2, C10, C11 dengan benar
     */
    public function scopeOrderByKodeNatural($query)
    {
        return $query->orderByRaw('LENGTH(kode), kode');
    }

    /**
     * Get the column name for this criteria in calon_mahasiswa table
     */
    public function getColumnName(): string
    {
        return strtolower($this->kode);
    }

    /**
     * Get all criteria column names
     */
    public static function getColumnNames(): array
    {
        return static::pluck('kode')->map(fn($code) => strtolower($code))->toArray();
    }

    /**
     * Check if criteria column exists in calon_mahasiswa table
     */
    public function columnExists(): bool
    {
        return Schema::hasColumn('calon_mahasiswa', $this->getColumnName());
    }

    /**
     * Get values for this criteria from all calon mahasiswa
     */
    public function getValuesFromCalonMahasiswa()
    {
        $column = $this->getColumnName();
        return \App\Models\CalonMahasiswa::pluck($column, 'id');
    }

    /**
     * Get options for this criteria (for select fields)
     */
    public function getOptions(): array
    {
        $kriteriaName = strtolower($this->nama);

        // Check if this is a location/housing criteria
        if (str_contains($kriteriaName, 'tempat tinggal') || str_contains($kriteriaName, 'lokasi') || str_contains($kriteriaName, 'kondisi')) {
            return [
                1 => '1 - Ngontrak/Kost',
                2 => '2 - Rumah Orangtua/Saudara',
                3 => '3 - Rumah Dinas',
                4 => '4 - Rumah Kredit',
                5 => '5 - Rumah Sendiri',
            ];
        }

        return [];
    }

    /**
     * Get formatted value for display
     */
    public function formatValue($value): string
    {
        if ($value === null) {
            return 'N/A';
        }

        $kriteriaName = strtolower($this->nama);

        // Format income/salary as currency
        if (str_contains($kriteriaName, 'penghasilan') || str_contains($kriteriaName, 'gaji') || str_contains($kriteriaName, 'income')) {
            return 'Rp ' . number_format($value, 0, ',', '.');
        }

        // Format location with options
        if (str_contains($kriteriaName, 'tempat tinggal') || str_contains($kriteriaName, 'lokasi') || str_contains($kriteriaName, 'kondisi')) {
            $options = $this->getOptions();
            return $options[$value] ?? "Tidak diketahui (nilai: {$value})";
        }

        // Format scores/percentages
        if (str_contains($kriteriaName, 'prestasi') || str_contains($kriteriaName, 'nilai') || str_contains($kriteriaName, 'wawancara')) {
            return number_format($value, 1);
        }

        // Format age
        if (str_contains($kriteriaName, 'usia') || str_contains($kriteriaName, 'umur')) {
            return $value . ' tahun';
        }

        // Format distance
        if (str_contains($kriteriaName, 'jarak')) {
            return number_format($value, 1) . ' km';
        }

        // Default formatting
        return is_numeric($value) ? number_format($value, 2) : $value;
    }

    /**
     * Get appropriate step for numeric input
     */
    public function getInputStep(): string
    {
        $kriteriaName = strtolower($this->nama);

        // Income - no decimal
        if (str_contains($kriteriaName, 'penghasilan') || str_contains($kriteriaName, 'gaji')) {
            return '1000';
        }

        // Location - integer
        if (str_contains($kriteriaName, 'tempat tinggal') || str_contains($kriteriaName, 'lokasi')) {
            return '1';
        }

        // Age - integer
        if (str_contains($kriteriaName, 'usia') || str_contains($kriteriaName, 'umur')) {
            return '1';
        }

        // Scores - allow decimal
        return '0.1';
    }
}
