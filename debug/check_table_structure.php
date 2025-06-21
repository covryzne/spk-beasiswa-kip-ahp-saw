<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== STRUKTUR TABEL HASIL_SELEKSI ===\n";

$columns = Schema::getColumnListing('hasil_seleksi');
echo "Kolom yang ada:\n";
foreach ($columns as $column) {
    echo "- {$column}\n";
}

echo "\n=== SAMPLE DATA HASIL_SELEKSI ===\n";
$sample = DB::table('hasil_seleksi')->first();
if ($sample) {
    foreach ((array)$sample as $field => $value) {
        echo "{$field}: " . ($value ?? 'NULL') . "\n";
    }
} else {
    echo "Tidak ada data\n";
}
