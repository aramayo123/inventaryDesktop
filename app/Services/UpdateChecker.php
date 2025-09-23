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
    protected $dbMigrationService;

    public function __construct(DatabaseMigrationService $dbMigrationService)
    {
        $this->dbMigrationService = $dbMigrationService;
    }

    // Versión actual de la app. Podés poner esto como una constante, o leerlo desde archivo si preferís.
    const CURRENT_VERSION = '1.0.5';

    public function check()
    {
        Log::debug("entro a check " );
        $owner = env('GITHUB_OWNER', 'aramayo123');
        $repo = env('GITHUB_REPO', 'inventaryDesktop');
        $token = env('GITHUB_TOKEN', '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');

        logger()->info("Verificando actualizaciones...");
        logger()->info("Versión actual: " . self::CURRENT_VERSION);

        $url = "https://api.github.com/repos/$owner/$repo/releases/latest";
        logger()->info("Consultando URL: " . $url);
        Log::alert("Consultando URL: " . $url);

        // Consulta GitHub con el token
        $response = Http::withToken($token)->get($url);

        if (!$response->successful()) {
            logger()->error("Error al consultar GitHub: " . $response->body());
            Log::alert("Error al consultar GitHub: " . $response->body());
            return;
        }

        $data = $response->json();
        $latestVersion = ltrim($data['tag_name'], 'v'); // por ejemplo "v1.1.0" → "1.1.0"
        $releaseUrl = $data['html_url']; // URL al release en GitHub
        $releaseNotes = $data['body'] ?? 'No hay notas de actualización disponibles.';

        logger()->info("Última versión disponible: " . $latestVersion);
        logger()->info("URL del release: " . $releaseUrl);
        
        Log::alert("URL del release: " . $releaseUrl);

        if (version_compare(self::CURRENT_VERSION, $latestVersion, '<')) {
            logger()->info("Nueva versión disponible. Mostrando diálogo de actualización.");
            $this->askUserToUpdate($latestVersion, $releaseUrl, $releaseNotes);
        } else {
            logger()->info("No hay nuevas versiones disponibles.");
        }
    }

    protected function askUserToUpdate(string $latestVersion, string $url, string $releaseNotes)
    {
        $currentVersion = self::CURRENT_VERSION;
        $detail = "Versión actual: $currentVersion\n" .
                 "Nueva versión: $latestVersion\n\n" .
                 "Notas de la actualización:\n" .
                 $releaseNotes . "\n\n" .
                 "IMPORTANTE: Se realizará un respaldo automático de su base de datos antes de la actualización.";

        Alert::new()
            ->title("Actualización disponible")
            ->type('info')
            ->detail($detail)
            ->buttons(['Actualizar ahora', 'Más tarde'])
            ->defaultId(0)
            ->show('¿Desea actualizar su programa?', function ($response) use ($url) {
                if ($response === 0) {
                    // Crear backup antes de la actualización
                    $backupFile = $this->dbMigrationService->createBackup();
                    
                    if ($backupFile) {
                        logger()->info("Backup creado exitosamente en: " . $backupFile);
                        
                        // Mostrar mensaje de éxito
                        Alert::new()
                            ->title("Backup creado")
                            ->type('success')
                            ->detail("Se ha creado un respaldo de su base de datos en:\n" . $backupFile)
                            ->buttons(['Continuar'])
                            ->show('Backup exitoso', function () use ($url) {
                                Shell::open($url);
                            });
                    } else {
                        // Mostrar advertencia si no se pudo crear el backup
                        Alert::new()
                            ->title("Advertencia")
                            ->type('warning')
                            ->detail("No se pudo crear un respaldo de la base de datos.\n¿Desea continuar con la actualización?")
                            ->buttons(['Continuar', 'Cancelar'])
                            ->show('Backup fallido', function ($response) use ($url) {
                                if ($response === 0) {
                                    Shell::open($url);
                                }
                            });
                    }
                }
                // Si elige "Más tarde", simplemente continuamos con la versión actual
                logger()->info("Usuario eligió continuar con la versión actual.");
            });
    }

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
            if (empty($release['assets'])) {
                $this->setProgress(-1, 'No se encontró ningún archivo en el release', 0);
                return ['success' => false, 'message' => 'No se encontró ningún archivo en el release'];
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

            // Paso 7: Importar datos de la base
            //$this->setProgress(7, 'Importando datos a la base...', 90);
            //$this->importDatabaseData();

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
            $jsonData = File::get($exportPath . '/' . $table . '.json');
            $data = json_decode($jsonData, true);
            foreach ($data as $row) {
                \DB::table($table)->insert($row);
            }
        }
    }
    /*
    protected function recurse_copy($src, $dst, $exclude = ['node_modules', 'vendor'])
    {
        // Si la carpeta actual está excluida, salimos
        foreach ($exclude as $skip) {
            if (str_contains($src, DIRECTORY_SEPARATOR . $skip)) {
                return;
            }
        }

        $dir = opendir($src);
        @mkdir($dst, 0777, true);

        while (false !== ($file = readdir($dir))) {
            if ($file != '.' && $file != '..') {
                $srcPath = $src . '/' . $file;
                $dstPath = $dst . '/' . $file;

                // Ignorar también por nombre exacto de carpeta en este nivel
                if (in_array($file, $exclude, true)) {
                    continue;
                }

                if (is_dir($srcPath)) {
                    $this->recurse_copy($srcPath, $dstPath, $exclude);
                } else {
                    copy($srcPath, $dstPath);
                }
            }
        }
        closedir($dir);
    }
    */
    
    protected function recurse_copy($src, $dst)
    {
        $excludes = [
            '.git',
            'node_modules',
            'vendor',
            '.DS_Store',
            'Thumbs.db',
            'bootstrap',
            'public',
            'storage',
            'tests',
        ];

        $dir = opendir($src);
        @mkdir($dst);

        while (false !== ($file = readdir($dir))) {
            if ($file != '.' && $file != '..') {
                $srcPath = $src . '/' . $file;
                $dstPath = $dst . '/' . $file;

                // si en cualquier parte del path aparece una carpeta a excluir → saltar
                foreach ($excludes as $skip) {
                    if (str_contains($srcPath, DIRECTORY_SEPARATOR . $skip)) {
                        continue 2; // saltar al próximo archivo/carpeta
                    }
                }

                if (is_dir($srcPath)) {
                    $this->recurse_copy($srcPath, $dstPath);
                } else {
                    @copy($srcPath, $dstPath);
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
