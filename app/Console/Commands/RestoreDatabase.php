<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DatabaseMigrationService;

class RestoreDatabase extends Command
{
    protected $signature = 'db:restore {backup? : El archivo de backup a restaurar}';
    protected $description = 'Restaura la base de datos desde un backup';

    public function handle(DatabaseMigrationService $dbMigrationService)
    {
        $backupFile = $this->argument('backup');

        if (!$backupFile) {
            // Si no se especifica un backup, mostrar lista de disponibles
            $latestBackup = $dbMigrationService->getLatestBackup();
            
            if (!$latestBackup) {
                $this->error('No se encontraron backups disponibles.');
                return 1;
            }

            $backupFile = $latestBackup;
            $this->info('Usando el backup más reciente: ' . basename($backupFile));
        }

        if ($dbMigrationService->restoreFromBackup($backupFile)) {
            $this->info('Base de datos restaurada exitosamente.');
            return 0;
        }

        $this->error('No se pudo restaurar la base de datos.');
        return 1;
    }
} 