<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Native\Laravel\Facades\Shell;
use Native\Laravel\Facades\Alert;
use App\Services\DatabaseMigrationService;

class UpdateChecker
{
    protected $dbMigrationService;

    public function __construct(DatabaseMigrationService $dbMigrationService)
    {
        $this->dbMigrationService = $dbMigrationService;
    }

    // Versión actual de la app. Podés poner esto como una constante, o leerlo desde archivo si preferís.
    const CURRENT_VERSION = '1.0.3';

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
}
