<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseMigrationService
{
    protected $backupPath;
    protected $databasePath;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        $this->databasePath = database_path('database.sqlite');
    }

    /**
     * Crea un backup de la base de datos actual
     */
    public function createBackup()
    {
        if (!File::exists($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }

        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $backupFile = $this->backupPath . "/database_backup_{$timestamp}.sqlite";

        // Copiar la base de datos actual
        File::copy($this->databasePath, $backupFile);

        return $backupFile;
    }

    /**
     * Restaura la base de datos desde un backup
     */
    public function restoreFromBackup($backupFile)
    {
        if (File::exists($backupFile)) {
            // Cerrar todas las conexiones a la base de datos
            DB::disconnect();

            // Restaurar el backup
            File::copy($backupFile, $this->databasePath);

            return true;
        }

        return false;
    }

    /**
     * Obtiene el último backup disponible
     */
    public function getLatestBackup()
    {
        if (!File::exists($this->backupPath)) {
            return null;
        }

        $files = File::files($this->backupPath);
        if (empty($files)) {
            return null;
        }

        // Ordenar por fecha de modificación (más reciente primero)
        usort($files, function($a, $b) {
            return File::lastModified($b) - File::lastModified($a);
        });

        return $files[0];
    }

    /**
     * Verifica si la base de datos necesita migración
     */
    public function needsMigration()
    {
        try {
            // Verificar si la tabla de migraciones existe
            $migrations = DB::table('migrations')->get();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Ejecuta las migraciones pendientes
     */
    public function runMigrations()
    {
        try {
            \Artisan::call('migrate', ['--force' => true]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
} 