<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatriksAhp extends Model
{
    use HasFactory;

    protected $table = 'matriks_ahp';

    protected $fillable = [
        'kriteria_1_id',
        'kriteria_2_id',
        'nilai',
    ];

    protected $casts = [
        'nilai' => 'decimal:7',
    ];

    /**
     * Get the first criteria
     */
    public function kriteria1(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_1_id');
    }

    /**
     * Get the second criteria
     */
    public function kriteria2(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_2_id');
    }

    /**
     * Get AHP scale options
     */
    public static function getScaleOptions(): array
    {
        return [
            1 => '1 - Sama penting',
            2 => '2 - Mendekati sedikit lebih penting',
            3 => '3 - Sedikit lebih penting',
            4 => '4 - Mendekati lebih penting',
            5 => '5 - Lebih penting',
            6 => '6 - Mendekati sangat penting',
            7 => '7 - Sangat penting',
            8 => '8 - Mendekati mutlak lebih penting',
            9 => '9 - Mutlak lebih penting',
        ];
    }

    /**
     * Get reciprocal scale options
     */
    public static function getReciprocalScaleOptions(): array
    {
        return [
            0.5 => '1/2 - Setengah penting',
            0.333333 => '1/3 - Sepertiga penting',
            0.25 => '1/4 - Seperempat penting',
            0.2 => '1/5 - Seperlima penting',
            0.166667 => '1/6 - Seperenam penting',
            0.142857 => '1/7 - Sepersepuluh penting',
            0.125 => '1/8 - Seperdelapan penting',
            0.111111 => '1/9 - Sepersembilan penting',
        ];
    }

    /**
     * Get all scale options (normal + reciprocal)
     */
    public static function getAllScaleOptions(): array
    {
        return array_merge(
            array_reverse(self::getReciprocalScaleOptions(), true),
            self::getScaleOptions()
        );
    }
}
