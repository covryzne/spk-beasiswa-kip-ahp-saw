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
        'c1', // Penghasilan Orang Tua
        'c2', // Tempat Tinggal
        'c3', // Hasil Tes Prestasi
        'c4', // Hasil Tes Wawancara
        'c5', // Rata-Rata Nilai
        'catatan',
    ];

    protected $casts = [
        'c1' => 'decimal:2',
        'c2' => 'integer',
        'c3' => 'decimal:2',
        'c4' => 'decimal:2',
        'c5' => 'decimal:2',
    ];

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
     * Get tempat tinggal label
     */
    public function getTempatTinggalLabelAttribute(): string
    {
        $options = self::getTempatTinggalOptions();
        return $options[$this->c2] ?? 'Tidak diketahui';
    }

    /**
     * Format penghasilan as currency
     */
    public function getPenghasilanFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->c1, 0, ',', '.');
    }

    /**
     * Get all criteria values as array
     */
    public function getCriteriaValues(): array
    {
        return [
            'c1' => $this->c1,
            'c2' => $this->c2,
            'c3' => $this->c3,
            'c4' => $this->c4,
            'c5' => $this->c5,
        ];
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
