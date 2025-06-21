<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
