<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CleanDemoDatabases extends Command
{
    protected $signature = 'demo:clean';

    protected $description = 'Borra las BBDD temporales de la demo de más de 1h';

    public function handle(): int
    {
        $directory = database_path('demos');

        if (! File::isDirectory($directory)) {
            $this->info('No demos directory found.');

            return self::SUCCESS;
        }

        $files = File::files($directory);
        $deletedCount = 0;

        foreach ($files as $file) {
            $filename = $file->getFilename();

            if ($filename === 'master.sqlite' || $file->getExtension() !== 'sqlite') {
                continue;
            }

            $lastModified = \Carbon\Carbon::createFromTimestamp($file->getMTime());

            if (now()->diffInHours($lastModified) >= 1) {
                try {
                    File::delete($file->getPathname());
                    $deletedCount++;
                } catch (\Exception $e) {
                    Log::warning('Failed to delete demo DB', [
                        'file' => $filename,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        if ($deletedCount > 0) {
            $this->info("Cleaned {$deletedCount} expired demo database(s).");
            Log::info('demo:clean completed', ['deleted' => $deletedCount]);
        }

        return self::SUCCESS;
    }
}
