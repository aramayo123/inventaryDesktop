<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\UpdateChecker;

class RunUpdateChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecuta el UpdateChecker en background';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);
        $checker = app(UpdateChecker::class);
        $checker->checkForUpdates();
        $this->info('UpdateChecker ejecutado.');
    }
}
