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

    protected $updateChecker;

    public function __construct(UpdateChecker $updateChecker)
    {
        $this->updateChecker = $updateChecker;
    }

    public function handle()
    {
        // Llamamos al método que ya actualiza el progreso en update_progress.json
        $this->updateChecker->checkForUpdates();
    }
}
