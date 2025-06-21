<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ClearAllSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-all-sessions {--browser : Show browser storage clearing instructions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all Laravel sessions and optionally show browser storage clearing instructions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Starting session cleanup process...');
        $this->newLine();

        // 1. Clear Laravel application cache
        $this->info('📋 Clearing Laravel application cache...');
        Artisan::call('cache:clear');
        $this->line('   ✅ Application cache cleared');

        // 2. Clear configuration cache
        $this->info('⚙️  Clearing configuration cache...');
        Artisan::call('config:clear');
        $this->line('   ✅ Configuration cache cleared');

        // 3. Clear route cache
        $this->info('🛣️  Clearing route cache...');
        Artisan::call('route:clear');
        $this->line('   ✅ Route cache cleared');

        // 4. Clear view cache
        $this->info('👁️  Clearing view cache...');
        Artisan::call('view:clear');
        $this->line('   ✅ View cache cleared');

        // 5. Clear session storage
        $this->info('🗂️  Clearing session storage...');
        $this->clearSessionFiles();
        $this->line('   ✅ Session files cleared');

        // 6. Clear database sessions (if using database driver)
        $this->info('🗄️  Clearing database sessions...');
        $this->clearDatabaseSessions();
        $this->line('   ✅ Database sessions cleared');

        // 7. Clear cookies session data
        $this->info('🍪 Clearing application session state...');
        Session::flush();
        $this->line('   ✅ Application session state cleared');

        $this->newLine();
        $this->info('✨ Laravel session cleanup completed successfully!');
        $this->newLine();

        // Show browser storage clearing instructions if requested
        if ($this->option('browser')) {
            $this->showBrowserStorageInstructions();
        } else {
            $this->warn('💡 Tip: Run with --browser flag to see browser storage clearing instructions');
            $this->warn('   Example: php artisan app:clear-all-sessions --browser');
        }

        return 0;
    }

    /**
     * Clear session files from storage
     */
    private function clearSessionFiles()
    {
        $sessionPath = storage_path('framework/sessions');

        if (File::exists($sessionPath)) {
            $files = File::files($sessionPath);
            foreach ($files as $file) {
                File::delete($file);
            }
            $this->line("   📁 Deleted " . count($files) . " session files from storage");
        } else {
            $this->line("   📁 Session directory not found or empty");
        }
    }

    /**
     * Clear database sessions if using database driver
     */
    private function clearDatabaseSessions()
    {
        try {
            $sessionDriver = config('session.driver');

            if ($sessionDriver === 'database') {
                $table = config('session.table', 'sessions');
                $deleted = DB::table($table)->delete();
                $this->line("   🗃️  Deleted {$deleted} session records from database table '{$table}'");
            } else {
                $this->line("   🗃️  Session driver is '{$sessionDriver}' - no database cleanup needed");
            }
        } catch (\Exception $e) {
            $this->line("   ⚠️  Could not clear database sessions: " . $e->getMessage());
        }
    }

    /**
     * Show comprehensive browser storage clearing instructions
     */
    private function showBrowserStorageInstructions()
    {
        $this->warn('🌐 BROWSER STORAGE CLEARING INSTRUCTIONS');
        $this->warn('=====================================');
        $this->newLine();

        $this->info('For complete session cleanup, you also need to clear browser storage:');
        $this->newLine();

        // Chrome Instructions
        $this->comment('🔵 Google Chrome:');
        $this->line('   1. Press F12 or right-click → Inspect');
        $this->line('   2. Go to Application tab');
        $this->line('   3. Under Storage section:');
        $this->line('      - Click "Local Storage" → Select your domain → Delete all');
        $this->line('      - Click "Session Storage" → Select your domain → Delete all');
        $this->line('      - Click "Cookies" → Select your domain → Delete all');
        $this->line('   4. Alternative: Press Ctrl+Shift+Del → Check all → Clear data');
        $this->newLine();

        // Firefox Instructions
        $this->comment('🔶 Mozilla Firefox:');
        $this->line('   1. Press F12 or right-click → Inspect Element');
        $this->line('   2. Go to Storage tab');
        $this->line('   3. Under each category:');
        $this->line('      - Right-click "Local Storage" → Delete All');
        $this->line('      - Right-click "Session Storage" → Delete All');
        $this->line('      - Right-click "Cookies" → Delete All');
        $this->line('   4. Alternative: Press Ctrl+Shift+Del → Check all → Clear Now');
        $this->newLine();

        // Edge Instructions
        $this->comment('🔷 Microsoft Edge:');
        $this->line('   1. Press F12 or right-click → Inspect');
        $this->line('   2. Go to Application tab');
        $this->line('   3. Same as Chrome instructions above');
        $this->line('   4. Alternative: Press Ctrl+Shift+Del → Check all → Clear now');
        $this->newLine();

        // JavaScript Console Method
        $this->comment('⚡ Quick Console Method (All Browsers):');
        $this->line('   1. Press F12 → Go to Console tab');
        $this->line('   2. Paste this JavaScript code:');
        $this->newLine();

        $jsCode = <<<'JS'
// Clear all storage types
localStorage.clear();
sessionStorage.clear();

// Clear all cookies for current domain
document.cookie.split(";").forEach(function(c) { 
    document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); 
});

console.log("✅ Browser storage cleared!");
location.reload();
JS;

        $this->line($jsCode);
        $this->newLine();
        $this->line('   3. Press Enter to execute');
        $this->line('   4. Page will refresh automatically');
        $this->newLine();

        // Additional Notes
        $this->comment('📝 Additional Notes:');
        $this->line('   • You may need to log in again after clearing browser storage');
        $this->line('   • Some preferences and settings may reset');
        $this->line('   • This is especially important after database migrations');
        $this->line('   • Clear browser storage if you see stale data or login issues');
        $this->newLine();

        // Auto-clear HTML page option
        $this->comment('🚀 Auto-Clear Option:');
        $this->line('   You can create a browser bookmark with this JavaScript:');
        $this->newLine();
        $bookmarkJs = "javascript:(function(){localStorage.clear();sessionStorage.clear();document.cookie.split(';').forEach(function(c){document.cookie=c.replace(/^ +/,'').replace(/=.*/,'=;expires='+new Date().toUTCString()+';path=/')});alert('✅ Browser storage cleared!');location.reload();})();";
        $this->line($bookmarkJs);
        $this->newLine();
        $this->line('   Save as bookmark and click it whenever you need to clear storage');
        $this->newLine();
    }
}
