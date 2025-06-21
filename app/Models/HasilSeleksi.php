<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilSeleksi extends Model
{
    use HasFactory;

    protected $table = 'hasil_seleksi';

    protected $fillable = [
        'calon_mahasiswa_id',
        'skor',
        'rank',
        'ranking',
        'status',
        'c1_normalized',
        'c2_normalized',
        'c3_normalized',
        'c4_normalized',
        'c5_normalized',
        'c6_normalized', // Additional fields for future criteria
        'c7_normalized',
        'c8_normalized',
        'c9_normalized',
        'c10_normalized',
        'normalized_values', // JSON field for completely dynamic storage
        'tanggal_seleksi',
    ];

    protected $casts = [
        'skor' => 'decimal:12',
        'c1_normalized' => 'decimal:12',
        'c2_normalized' => 'decimal:12',
        'c3_normalized' => 'decimal:12',
        'c4_normalized' => 'decimal:12',
        'c5_normalized' => 'decimal:12',
        'c6_normalized' => 'decimal:12',
        'c7_normalized' => 'decimal:12',
        'c8_normalized' => 'decimal:12',
        'c9_normalized' => 'decimal:12',
        'c10_normalized' => 'decimal:12',
        'normalized_values' => 'array', // JSON field
        'tanggal_seleksi' => 'date',
        'rank' => 'integer',
        'ranking' => 'integer',
    ];

    /**
     * Get the calon mahasiswa that owns this hasil seleksi
     */
    public function calonMahasiswa(): BelongsTo
    {
        return $this->belongsTo(CalonMahasiswa::class);
    }

    /**
     * Get normalized values as array (dynamic based on existing criteria)
     */
    public function getNormalizedValues(): array
    {
        // First try to get from JSON field (most flexible)
        if (!empty($this->normalized_values)) {
            return $this->normalized_values;
        }

        // Fallback to individual columns
        $kriteria = \App\Models\Kriteria::orderBy('kode')->get();
        $normalizedValues = [];

        foreach ($kriteria as $k) {
            $fieldName = strtolower($k->kode); // c1, c2, c3, etc.
            $normalizedFieldName = $fieldName . '_normalized';
            $normalizedValues[$fieldName] = $this->{$normalizedFieldName} ?? 0;
        }

        return $normalizedValues;
    }

    /**
     * Get normalized value for specific criteria
     */
    public function getNormalizedValue(string $criteriaCode): float
    {
        $normalizedValues = $this->getNormalizedValues();
        $fieldName = strtolower($criteriaCode);
        return (float) ($normalizedValues[$fieldName] ?? 0);
    }

    /**
     * Get rank with suffix (1st, 2nd, 3rd, etc.)
     */
    public function getRankWithSuffixAttribute(): string
    {
        $rank = $this->rank;

        if ($rank % 100 >= 11 && $rank % 100 <= 13) {
            $suffix = 'th';
        } else {
            switch ($rank % 10) {
                case 1:
                    $suffix = 'st';
                    break;
                case 2:
                    $suffix = 'nd';
                    break;
                case 3:
                    $suffix = 'rd';
                    break;
                default:
                    $suffix = 'th';
                    break;
            }
        }

        return $rank . $suffix;
    }

    /**
     * Scope to get ordered by rank
     */
    public function scopeOrderedByRank($query)
    {
        return $query->orderBy('rank');
    }

    /**
     * Scope to get ordered by score (desc)
     */
    public function scopeOrderedByScore($query)
    {
        return $query->orderBy('skor', 'desc');
    }

    /**
     * Scope to get latest seleksi by date
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('tanggal_seleksi', 'desc');
    }
}
