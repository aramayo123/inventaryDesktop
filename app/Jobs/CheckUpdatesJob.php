<?php

namespace App\Jobs;

use App\Services\UpdateChecker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckUpdatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(UpdateChecker $updateChecker)
    {
        // Laravel inyecta UpdateChecker aquí automáticamente
        $updateChecker->checkForUpdates();
    }
    /*
    public function failed(\Throwable $exception): void
    {
        \Log::error("CheckUpdatesJob falló", [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
    */
    
}
