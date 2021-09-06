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
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Onejav
        $schedule->command('jav:onejav release')->everyFiveMinutes();
        $schedule->command('jav:onejav daily')->dailyAt('12:00');

        // R18
        $schedule->command('jav:r18 release')->everyFiveMinutes();
        $schedule->command('jav:r18 daily')->dailyAt('12:00');
        $schedule->command('jav:r18 item');

        // XCity Idol
        $schedule->command('jav:xcity-idol release')->everyFifteenMinutes();
        $schedule->command('jav:xcity-idol daily')->dailyAt('12:00');
        $schedule->command('jav:xcity-idol item');
        $schedule->command('jav:xcity-video release')->everyFifteenMinutes();
        $schedule->command('jav:xcity-video daily')->dailyAt('12:00');
        $schedule->command('jav:xcity-video item');

        $schedule->command('queue:prune-failed --hours=168');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load([
            __DIR__.'/Commands',
            __DIR__ . '/../Core/Console/Commands',
            __DIR__.'/../Jav/Console/Commands',
            __DIR__ . '/../Flickr/Console/Commands',
        ]);

        require base_path('routes/console.php');
    }
}
