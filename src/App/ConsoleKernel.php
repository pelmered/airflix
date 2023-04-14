<?php
namespace App;

use App\Jobs\ElasticsearchReindex;
use App\Jobs\ResetAPIRequestsDailyMetric;
use App\Jobs\ResetAPIRequestsHourlyMetric;
use App\Jobs\ResetAPIRequestsMonthlyMetric;
use App\Jobs\ResetAPIRequestsWeeklyMetric;
use Carbon\Carbon;
use Domain\CRMSync\Jobs\SyncAllUsersToHubspotJob;
use Domain\Reports\Jobs\WeeklyKPIsJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Spatie\Health\Commands\ScheduleCheckHeartbeatCommand;
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
        //$schedule->command('heap:check-overdue-bookings')->everyTenMinutes();
        //$schedule->command('CancelUnstartedBookings')->everyTenMinutes();
        //$schedule->command('heap:migrate-booking-totals')->everyTenMinutes();

        // Full backups
        /*
        $schedule->call(fn () => Artisan::queue('backup:clean')->onQueue('low'))
            ->daily()->at('02:45');
        $schedule->call(fn () => Artisan::queue('backup:run')->onQueue('low'))
            ->daily()->at('02:30')->graceTimeInMinutes(15);
        $schedule->call(fn () => Artisan::queue('backup:monitor')->onQueue('low'))
            ->daily()->at('09:00');
        */
        $schedule->command('backup:clean')->daily()->at('02:45');
        $schedule->command('backup:run')->daily()->at('02:35')->graceTimeInMinutes(15);
        $schedule->command('backup:monitor')->daily()->at('09:05');

        $schedule->command('personal-data-export:clean')->daily();

        //Kpi
        $schedule->command('kpi:create-last-month-history')->monthlyOn(1, '00:05');
        $schedule->command('kpi:create-last-year-history')->yearlyOn(1, 1, '00:07');

        // Database snapshots
        /*
        $schedule->call(fn () => QueuedCommand::dispatch('snapshot:create')->onQueue('low'))
            ->everyFiveMinutes()->graceTimeInMinutes(10);
        */
        $schedule->call(fn () => Artisan::queue('snapshot:create')->onQueue('low'))
            ->everyFiveMinutes()->graceTimeInMinutes(10);
        $schedule->call(fn () => Artisan::queue('snapshot:rotate')->onQueue('low'))
            ->dailyAt('02:35')->graceTimeInMinutes(10);
        $schedule->call(fn () => Artisan::queue('heap:send-logs-to-remote')->onQueue('low'))
            ->twiceDaily(0, 12)->graceTimeInMinutes(20);

        /*
        $schedule->command('snapshot:create')->everyFiveMinutes()->graceTimeInMinutes(10);
        $schedule->command('snapshot:rotate')->dailyAt('02:30')->graceTimeInMinutes(10);
        $schedule->command('heap:send-logs-to-remote')->twiceDaily(0, 12)->graceTimeInMinutes(20);
        */

        // Add Cloudflare ip addresses to trusted proxies
        // https://github.com/monicahq/laravel-cloudflare
        $schedule->command('cloudflare:reload')->dailyAt('00:35');

        // TODO: Fix S3(or similar) before activating
        // See: https://docs.spatie.be/laravel-backup/v5/monitoring-the-health-of-all-backups/overview
        //$schedule->command('backup:monitor')->daily()->at('04:30');

        // TODO: Set up separate monitor installation
        //$schedule->command('server-monitor:run-checks')->withoutOverlapping()->everyMinute();

        // Rest API requests metric
        /*
        $schedule->command('heap:reset-api-metrics hourly')->hourlyAt(0);
        $schedule->command('heap:reset-api-metrics today')->daily()->at('00:00');
        $schedule->command('heap:reset-api-metrics weekly')->weeklyOn(1, '00:00');
        $schedule->command('heap:reset-api-metrics monthly')->monthlyOn(1, '00:00');
        */
        $schedule->job(new ResetAPIRequestsHourlyMetric())->hourlyAt(0);
        $schedule->job(new ResetAPIRequestsDailyMetric())->dailyAt('00:00');
        $schedule->job(new ResetAPIRequestsWeeklyMetric())->weeklyOn(1);
        $schedule->job(new ResetAPIRequestsMonthlyMetric())->monthlyOn(1);

        // Update Horizon metrics

        $schedule->call(fn () => Artisan::call('horizon:snapshot'))->everyTenMinutes()->graceTimeInMinutes(10);
        //$schedule->command('horizon:snapshot')->everyFiveMinutes()->graceTimeInMinutes(7);

        $schedule->job(new WeeklyKPIsJob())->weeklyOn(5, '05:05');

        // Application tasks
        //$schedule->command('heap:update-ratings')->daily()->at('03:00');
        //$schedule->command('search:reindex')->daily()->at('03:30');

        $schedule->job(new ElasticsearchReindex())->daily()->at('03:35');

        // CloudBoxx queue listeners
        /*
        These are running as deamons in Laravel Forge.
        $schedule->command('cloudboxx:events-listener')->withoutOverlapping()->everyMinute();
        $schedule->command('cloudboxx:trackings-listener')->withoutOverlapping()->everyMinute();
        //$schedule->command('cloudboxx:heartbeat-listener')->withoutOverlapping()->everyMinute();
        */

        // Sync all users to Hubspot
        $schedule->job(new SyncAllUsersToHubspotJob())->daily()->at('04:35');

        // Monitor heartbeat
        $schedule->command(ScheduleCheckHeartbeatCommand::class)->everyMinute();

        $this->logScheduleRun();
    }

    protected function logScheduleRun(): void
    {
        $now      = now();
        $lastRun  = Cache::get('last_schedule_run');
        $duration = $lastRun
            ? Carbon::createFromTimestamp($lastRun)->diffInSeconds($now).'s'
            : 'No timestamp for last run';

        Log::channel('schedule')
            ->debug(
                'Schedule run complete. Seconds since last run: '.$duration.
                ' Last heartbeat at: '.Cache::get('health.checks.schedule.latestHeartbeatAt')
            );

        cache()->set('last_schedule_run', $now->timestamp);
    }
}
