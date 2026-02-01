<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupTempFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-temp-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup temporary files and old exports older than 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting temporary file cleanup...');

        $tempPath = 'temp'; // Relative to storage/app usually
        
        // Ensure directory exists to avoid errors
        if (!Storage::exists($tempPath)) {
            $this->info("Temp directory '$tempPath' does not exist. Skipping.");
            return;
        }

        $files = Storage::files($tempPath);
        $count = 0;

        foreach ($files as $file) {
            $lastModified = Storage::lastModified($file);
            
            // Check if older than 24 hours
            if (now()->subHours(24)->timestamp > $lastModified) {
                Storage::delete($file);
                $this->info("Deleted: $file");
                $count++;
            }
        }

        $this->info("Cleanup complete. $count files deleted.");
    }
}
