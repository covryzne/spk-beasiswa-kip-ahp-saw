<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerhitunganAhp extends Model
{
    use HasFactory;

    protected $table = 'perhitungan_ahp';

    protected $fillable = [
        'lambda_max',
        'ci',
        'ri',
        'cr',
        'is_consistent',
        'eigen_vector',
        'matriks_normalized',
        'weighted_sum', // Add weighted sum for debugging
        'tanggal_perhitungan',
    ];

    protected $casts = [
        'lambda_max' => 'decimal:7',
        'ci' => 'decimal:7',
        'ri' => 'decimal:7',
        'cr' => 'decimal:7',
        'is_consistent' => 'boolean',
        'eigen_vector' => 'array',
        'matriks_normalized' => 'array',
        'weighted_sum' => 'array', // Add weighted sum cast
        'tanggal_perhitungan' => 'date',
    ];

    /**
     * Get Random Index (RI) values for different matrix sizes
     * Values match Excel calculation standard
     */
    public static function getRandomIndex(): array
    {
        return [
            1 => 0.00,
            2 => 0.00,
            3 => 0.52,
            4 => 0.89,
            5 => 1.11, // Fixed to match Excel (was 1.12)
            6 => 1.25,
            7 => 1.35,
            8 => 1.40,
            9 => 1.45,
            10 => 1.49,
        ];
    }

    /**
     * Get RI value for matrix size n
     */
    public static function getRiForSize(int $n): float
    {
        $riValues = self::getRandomIndex();
        return $riValues[$n] ?? 0;
    }

    /**
     * Check if the calculation is consistent (CR < 0.1)
     */
    public function isConsistent(): bool
    {
        return $this->cr < 0.1;
    }

    /**
     * Get consistency status with label
     */
    public function getConsistencyStatusAttribute(): string
    {
        return $this->isConsistent() ? 'Konsisten' : 'Tidak Konsisten';
    }

    /**
     * Get consistency status color for UI
     */
    public function getConsistencyColorAttribute(): string
    {
        return $this->isConsistent() ? 'success' : 'error';
    }

    /**
     * Get CR percentage
     */
    public function getCrPercentageAttribute(): string
    {
        return number_format($this->cr * 100, 2) . '%';
    }

    /**
     * Scope to get latest calculation
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('tanggal_perhitungan', 'desc');
    }

    /**
     * Scope to get consistent calculations only
     */
    public function scopeConsistent($query)
    {
        return $query->where('is_consistent', true);
    }
}
