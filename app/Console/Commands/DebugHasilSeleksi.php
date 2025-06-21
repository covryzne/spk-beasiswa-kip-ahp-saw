<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HasilSeleksi;
use App\Models\CalonMahasiswa;

class DebugHasilSeleksi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:hasil-seleksi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug HasilSeleksi data for troubleshooting';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== DEBUG HASIL SELEKSI ===');

        $countHasil = HasilSeleksi::count();
        $countCalon = CalonMahasiswa::count();

        $this->info("Total HasilSeleksi: {$countHasil}");
        $this->info("Total CalonMahasiswa: {$countCalon}");

        if ($countHasil > 0) {
            $this->info("\n--- SAMPLE DATA ---");

            $hasil = HasilSeleksi::with('calonMahasiswa')->first();

            $this->info("HasilSeleksi ID: {$hasil->id}");
            $this->info("Ranking: " . ($hasil->ranking ?? 'NULL'));
            $this->info("Rank: " . ($hasil->rank ?? 'NULL'));
            $this->info("Skor: " . ($hasil->skor ?? 'NULL'));
            $this->info("Status: " . ($hasil->status ?? 'NULL'));
            $this->info("Calon Mahasiswa ID: {$hasil->calon_mahasiswa_id}");

            if ($hasil->calonMahasiswa) {
                $this->info("Nama: {$hasil->calonMahasiswa->nama}");
                $this->info("Kode: {$hasil->calonMahasiswa->kode}");
                $this->success("✅ Relasi calonMahasiswa OK!");
            } else {
                $this->error("❌ Relasi calonMahasiswa NULL!");

                // Check if calon_mahasiswa exists
                $calon = CalonMahasiswa::find($hasil->calon_mahasiswa_id);
                if ($calon) {
                    $this->warn("⚠️ CalonMahasiswa with ID {$hasil->calon_mahasiswa_id} exists but relation failed");
                } else {
                    $this->error("❌ CalonMahasiswa with ID {$hasil->calon_mahasiswa_id} NOT found!");
                }
            }

            $this->info("\n--- TOP 5 HASIL SELEKSI ---");
            $top5 = HasilSeleksi::with('calonMahasiswa')->orderBy('ranking')->take(5)->get();

            foreach ($top5 as $h) {
                $nama = $h->calonMahasiswa ? $h->calonMahasiswa->nama : 'NULL';
                $kode = $h->calonMahasiswa ? $h->calonMahasiswa->kode : 'NULL';
                $this->line("Rank {$h->ranking}: {$nama} ({$kode}) - Score: {$h->skor} - Status: {$h->status}");
            }
        } else {
            $this->warn("No HasilSeleksi data found!");
        }

        return 0;
    }
}
