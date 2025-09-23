<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Native\Laravel\Facades\Shell;
use Native\Laravel\Facades\Alert;
use App\Services\DatabaseMigrationService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class UpdateChecker
{

    // Helper para progreso
    protected function setProgress($step, $msg, $percent = null) {
        $filePath = storage_path('app/update_progress.json');

        // Crear carpeta si no existe
        $dir = dirname($filePath);
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $progress = [
            'step' => $step,
            'msg' => $msg,
            'percent' => $percent
        ];

        file_put_contents($filePath, json_encode($progress));
    }

    public static function getProgress() {
        $file = storage_path('app/update_progress.json');
        //Log::info("paso por getprogress $file");
        if (file_exists($file)) {
            return json_decode(file_get_contents($file), true);
        }
        return ['step' => 0, 'msg' => 'Esperando...', 'percent' => 0];
    }
    public function checkForUpdates()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);
        $backupFile = null;

        try {
            // Paso 1: Consultando GitHub
            $this->setProgress(1, 'Consultando GitHub...', 5);
            $response = Http::get('https://api.github.com/repos/aramayo123/inventaryDesktop/releases/latest');

            if (!$response->successful()) {
                $this->setProgress(-1, 'No se pudo obtener el release de GitHub', 0);
                return ['success' => false, 'message' => 'No se pudo obtener el release de GitHub'];
            }

            $release = $response->json();
            $newVersion = ltrim($release['tag_name'], 'v'); // Ej: "v2.9" → "2.9"
            $currentVersion = config('app.current_version');
            if (empty($release['assets'])) {
                $this->setProgress(-1, 'No se encontró ningún archivo en el release', 0);
                return ['success' => false, 'message' => 'No se encontró ningún archivo en el release'];
            }
            if (version_compare($newVersion, $currentVersion, '<=')) {
                $this->setProgress(8, "Ya estás en la última versión ($currentVersion)", 100);
                return ['success' => false, 'message' => "Ya estás en la última versión ($currentVersion)"];
            }

            // Paso 2: Descargar el ZIP
            $this->setProgress(2, 'Descargando actualización...', 10);
            $asset = $release['assets'][0];
            $downloadUrl = $asset['browser_download_url'];
            $filename = basename(parse_url($downloadUrl, PHP_URL_PATH));
            $localZip = storage_path('app/tmp_update/' . $filename);

            if (!File::exists(storage_path('app/tmp_update'))) {
                File::makeDirectory(storage_path('app/tmp_update'), 0755, true);
            }

            $fileContent = Http::timeout(300)->get($downloadUrl)->body();
            File::put($localZip, $fileContent);

            // Paso 3: Crear backup de la base de datos
            $this->setProgress(3, 'Creando backup de la base de datos...', 20);
            $backupFile = $this->backupDatabase();

            // Paso 4: Exportar datos de la base
            $this->setProgress(4, 'Exportando datos de la base de datos...', 30);
            $this->exportDatabaseData();

            // Paso 5: Descomprimir ZIP
            $this->setProgress(5, 'Descomprimiendo actualización...', 50);
            $zip = new \ZipArchive();
            if ($zip->open($localZip) !== TRUE) {
                $this->setProgress(-1, 'No se pudo descomprimir el ZIP. Backup restaurado.', 0);
                $this->restoreDatabase($backupFile);
                return ['success' => false, 'message' => 'No se pudo descomprimir el ZIP'];
            }

            $extractPath = storage_path('app/tmp_update/extracted');
            if (!File::exists($extractPath)) {
                File::makeDirectory($extractPath, 0755, true);
            }

            $zip->extractTo($extractPath);
            $zip->close();

            // Paso 6: Reemplazar solo resources/app
            $this->setProgress(6, 'Reemplazando archivos del sistema...', 70);
            $appFromZip = $extractPath . '/dist/win-unpacked/resources/app.asar.unpacked/resources/app';
            $destProject = base_path();
            $this->recurse_copy($appFromZip, $destProject);
            
            // 🚨 Nuevo paso: Migraciones
            $this->setProgress(6.5, 'Ejecutando migraciones...', 80);
            \Artisan::call('migrate', ['--force' => true]);
            
            // Paso 7: Importar datos de la base
            $this->setProgress(7, 'Importando datos a la base...', 90);
            $this->importDatabaseData();

            // Paso final
            $this->setProgress(8, '¡Actualización completada!', 100);
            return ['success' => true, 'message' => 'Actualización completada'];

        } catch (\Exception $e) {
            if ($backupFile) {
                $this->restoreDatabase($backupFile);
            }
            $this->setProgress(-1, 'Error: ' . $e->getMessage() . '. Backup restaurado.', 0);
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    protected function backupDatabase()
    {
        $backupPath = storage_path('app/backups');
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $backupFile = $backupPath . '/' . $filename;
        $command = sprintf(
            'mysqldump -u%s -p%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            $backupFile
        );

        exec($command);
        return $backupFile;
    }

    protected function exportDatabaseData()
    {
        $tables = ['clients', 'products', 'ventas', 'facturas'];
        $exportPath = storage_path('app/tmp_update/database_export');
        if (!File::exists($exportPath)) {
            File::makeDirectory($exportPath, 0755, true);
        }

        foreach ($tables as $table) {
            $data = \DB::table($table)->get();
            File::put($exportPath . '/' . $table . '.json', json_encode($data));
        }
    }

    protected function importDatabaseData()
    {
        $tables = ['clients', 'products', 'ventas', 'facturas'];
        $exportPath = storage_path('app/tmp_update/database_export');

        foreach ($tables as $table) {
            $jsonFile = $exportPath . '/' . $table . '.json';
            if (!File::exists($jsonFile)) {
                continue; // si no existe el JSON, saltar
            }

            $jsonData = File::get($jsonFile);
            $data = json_decode($jsonData, true);

            foreach ($data as $row) {
                // Llenar columnas nuevas que no existan en el row
                $columns = \Schema::getColumnListing($table); // todas las columnas actuales de la tabla
                foreach ($columns as $col) {
                    if (!array_key_exists($col, $row)) {
                        $row[$col] = null; // valor por defecto para columnas nuevas
                    }
                }

                // Insertar o actualizar según id
                \DB::table($table)->updateOrInsert(
                    ['id' => $row['id']],
                    $row
                );
            }
        }
    }
    protected function recurse_copy($src, $dst)
    {
        // Carpetas o archivos que querés excluir (solo nombres directos)
        $excludes = [
            '.git',
            'node_modules',
            'vendor',
            '.DS_Store',
            'Thumbs.db',
            'bootstrap',
            'storage',
            'tests',
        ];

        // Si no existe el destino, lo creamos
        if (!File::exists($dst)) {
            File::makeDirectory($dst, 0755, true);
        }

        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            $srcPath = $src . DIRECTORY_SEPARATOR . $file;
            $dstPath = $dst . DIRECTORY_SEPARATOR . $file;

            // Excluir solo si el nombre directo está en la lista
            if (in_array($file, $excludes)) {
                Log::info("🚫 Excluido: " . realpath($srcPath));
                continue;
            }

            if (is_dir($srcPath)) {
                $this->recurse_copy($srcPath, $dstPath);
            } else {
                // Si ya existe el archivo destino, lo eliminamos
                if (file_exists($dstPath)) {
                    unlink($dstPath);
                }

                if (!copy($srcPath, $dstPath)) {
                    Log::error("❌ Error copiando " . realpath($srcPath) . " -> " . $dstPath);
                } else {
                    Log::info("✅ Copiado: " . realpath($srcPath) . " -> " . realpath($dstPath));
                }
            }
        }
        closedir($dir);
    }
    protected function restoreDatabase($backupFile)
    {
        $command = sprintf(
            'mysql -u%s -p%s %s < %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            $backupFile
        );

        exec($command);
    }
}
