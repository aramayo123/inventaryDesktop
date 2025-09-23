<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //\App\Console\Commands\UpdateSystem::class, 
        //\App\Console\Commands\RunUpdateChecker::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * These schedules are run in the background and are not user-interactive.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire:daily')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        $this->app->booted(function () {
            $this->commands = array_filter(
                $this->commands,
                fn ($command) => $command !== \Illuminate\Foundation\Console\StorageLinkCommand::class
            );
        });

        require base_path('routes/console.php');
    }
} 