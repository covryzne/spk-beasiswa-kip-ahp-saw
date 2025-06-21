<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\HasilSeleksi;
use App\Models\CalonMahasiswa;

echo "=== DEBUG HASIL SELEKSI ===\n";
echo "Total Hasil Seleksi: " . HasilSeleksi::count() . "\n\n";

// Ambil semua hasil seleksi dengan relasi
$hasilSeleksi = HasilSeleksi::with('calonMahasiswa')->get();

if ($hasilSeleksi->count() > 0) {
    echo "Detail Hasil Seleksi:\n";
    foreach ($hasilSeleksi as $hasil) {
        echo "- Calon Mahasiswa ID: {$hasil->calon_mahasiswa_id}\n";
        echo "  Nama: " . ($hasil->calonMahasiswa ? $hasil->calonMahasiswa->nama : 'NULL') . "\n";
        echo "  Kode: " . ($hasil->calonMahasiswa ? $hasil->calonMahasiswa->kode : 'NULL') . "\n";
        echo "  Rank: {$hasil->rank}\n";
        echo "  Skor: {$hasil->skor}\n";
        echo "  Skor Akhir: {$hasil->skor_akhir}\n";
        echo "  Method SAW: {$hasil->method_saw}\n";
        echo "  Status: {$hasil->status}\n";
        echo "  Created At: {$hasil->created_at}\n\n";
    }
} else {
    echo "❌ Tidak ada data hasil seleksi!\n";
    echo "💡 Silakan jalankan perhitungan SAW/AHP terlebih dahulu di panel admin.\n";
}

// Cek calon mahasiswa yang sudah ada
echo "\n=== CALON MAHASISWA ===\n";
$calonMahasiswa = CalonMahasiswa::with('hasilSeleksi')->limit(3)->get();

foreach ($calonMahasiswa as $calon) {
    echo "- {$calon->kode}: {$calon->nama}\n";
    if ($calon->hasilSeleksi) {
        echo "  Hasil Seleksi: Rank #{$calon->hasilSeleksi->rank}\n";
        echo "  Skor: {$calon->hasilSeleksi->skor}\n";
        echo "  Skor Akhir: {$calon->hasilSeleksi->skor_akhir}\n";
        echo "  Method: {$calon->hasilSeleksi->method_saw}\n";
        echo "  Status: {$calon->hasilSeleksi->status}\n\n";
    } else {
        echo "  Hasil Seleksi: Belum ada\n\n";
    }
}
