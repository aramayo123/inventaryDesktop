<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Native\Laravel\Facades\Shell;
use Native\Laravel\Facades\Alert;
use App\Services\DatabaseMigrationService;
use Illuminate\Support\Facades\File;

class UpdateChecker
{
    protected $dbMigrationService;

    public function __construct(DatabaseMigrationService $dbMigrationService)
    {
        $this->dbMigrationService = $dbMigrationService;
    }

    // Versión actual de la app. Podés poner esto como una constante, o leerlo desde archivo si preferís.
    const CURRENT_VERSION = '1.0.0';

    public function check()
    {
        $owner = env('GITHUB_OWNER', 'aramayo123');
        $repo = env('GITHUB_REPO', 'inventaryDesktop');
        $token = env('GITHUB_TOKEN', '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');

        logger()->info("Verificando actualizaciones...");
        logger()->info("Versión actual: " . self::CURRENT_VERSION);

        $url = "https://api.github.com/repos/$owner/$repo/releases/latest";
        logger()->info("Consultando URL: " . $url);

        // Consulta GitHub con el token
        $response = Http::withToken($token)->get($url);

        if (!$response->successful()) {
            logger()->error("Error al consultar GitHub: " . $response->body());
            return;
        }

        $data = $response->json();
        $latestVersion = ltrim($data['tag_name'], 'v'); // por ejemplo "v1.1.0" → "1.1.0"
        $releaseUrl = $data['html_url']; // URL al release en GitHub
        $releaseNotes = $data['body'] ?? 'No hay notas de actualización disponibles.';

        logger()->info("Última versión disponible: " . $latestVersion);
        logger()->info("URL del release: " . $releaseUrl);

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

    public function checkForUpdates()
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'InventaryDesktop-Updater'
            ])->get('https://api.github.com/repos/aramayo123/inventaryDesktop/releases/latest');

            if ($response->successful()) {
                $release = $response->json();
                if (isset($release['assets'][0]['browser_download_url'])) {
                    $downloadUrl = $release['assets'][0]['browser_download_url'];
                    $filename = basename(parse_url($downloadUrl, PHP_URL_PATH));
                    $localZip = storage_path('app/tmp_update/' . $filename);

                    // Crear directorio temporal si no existe
                    if (!File::exists(storage_path('app/tmp_update'))) {
                        File::makeDirectory(storage_path('app/tmp_update'), 0755, true);
                    }

                    // Descargar el archivo
                    $fileContent = Http::withHeaders([
                        'User-Agent' => 'InventaryDesktop-Updater'
                    ])->get($downloadUrl)->body();

                    File::put($localZip, $fileContent);

                    // Hacer backup de la base de datos
                    $backupFile = $this->backupDatabase();

                    // Exportar datos de la vieja base de datos
                    $this->exportDatabaseData();

                    // Descomprimir el archivo ZIP
                    $zip = new \ZipArchive();
                    if ($zip->open($localZip) === TRUE) {
                        $extractPath = storage_path('app/tmp_update/extracted');
                        if (!File::exists($extractPath)) {
                            File::makeDirectory($extractPath, 0755, true);
                        }
                        $zip->extractTo($extractPath);
                        $zip->close();

                        // Reemplazar archivos
                        $this->recurse_copy($extractPath, base_path());

                        // Importar datos a la nueva base de datos
                        $this->importDatabaseData();

                        return [
                            'success' => true,
                            'message' => 'Actualización completada. Archivos reemplazados y datos importados correctamente.'
                        ];
                    } else {
                        // Restaurar backup si falla la descompresión
                        $this->restoreDatabase($backupFile);
                        return [
                            'success' => false,
                            'message' => 'No se pudo descomprimir el archivo ZIP. Backup restaurado.'
                        ];
                    }
                }
            }

            return [
                'success' => false,
                'message' => 'No se pudo obtener el release de GitHub'
            ];
        } catch (\Exception $e) {
            // Restaurar backup si ocurre cualquier error
            if (isset($backupFile)) {
                $this->restoreDatabase($backupFile);
            }
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage() . '. Backup restaurado.'
            ];
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

    protected function recurse_copy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
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
