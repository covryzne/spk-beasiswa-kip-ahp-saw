<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\SawService;

echo "=== RUNNING SAW CALCULATION ===\n";

try {
    $sawService = app(SawService::class);
    $result = $sawService->calculateSaw();

    echo "✅ SAW calculation completed successfully!\n";
    echo "📊 Results: " . count($result['ranking']) . " candidates ranked\n\n";

    // Show top 5 results
    echo "🏆 TOP 5 RANKING:\n";
    $top5 = array_slice($result['ranking'], 0, 5);

    foreach ($top5 as $item) {
        echo "#{$item['rank']} - ID: {$item['calon_mahasiswa_id']}, Skor: " . number_format($item['skor'], 4) . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
