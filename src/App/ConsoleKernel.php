<?php

namespace App;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel;
use Support\AutoRegisterDomains\RegisterDomainCommands;

class ConsoleKernel extends Kernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Console/Commands');

        RegisterDomainCommands::register();

        require base_path('routes/console.php');
    }

    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
    }
}
