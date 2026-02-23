<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CleanDemoDatabases extends Command
{
    protected $signature = 'demo:clean';
    protected $description = 'Borra las BBDD temporales de la demo de más de 24h';

    public function handle()
    {
        $directory = database_path('demos');
        $files = File::files($directory);
        $deletedCount = 0;

        foreach ($files as $file) {
            $filename = $file->getFilename();

            // 1. Ignorar el maestro y archivos que no sean sqlite
            if ($filename === 'master.sqlite' || $file->getExtension() !== 'sqlite') {
                continue;
            }

            // 2. Verificar antigüedad (24 horas)
            $lastModified = \Carbon\Carbon::createFromTimestamp($file->getMTime());

            if (now()->diffInHours($lastModified) >= 1) {
                try {
                    File::delete($file->getPathname());
                    $deletedCount++;
                } catch (\Exception $e) {
                    // Silencioso en producción para no llenar logs de errores de Windows
                }
            }
        }

        if ($deletedCount > 0) {
            $this->info("Limpieza terminada. Se eliminaron {$deletedCount} bases de datos obsoletas.");
        }
    }
}
