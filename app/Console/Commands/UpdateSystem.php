<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\UpdateChecker;

class UpdateSystem extends Command
{
    protected $signature = 'actualizar:sistema';
    protected $description = 'Actualiza el sistema y muestra el progreso';

    public function handle(UpdateChecker $updateChecker)
    {
        $updateChecker->checkForUpdates();
        $this->info('Proceso de actualización lanzado.');
        return 0;
    }
} 