<?php

namespace App\Core\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use function base_path;

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
        $schedule->command('jav:onejav release')->everyMinute();
        $schedule->command('jav:onejav daily')->dailyAt('14:00');

        // R18
        $schedule->command('jav:r18 release')->everyMinute();
        $schedule->command('jav:r18 daily')->dailyAt('12:00');
        $schedule->command('jav:r18 item');

        // XCity Idol
        $schedule->command('jav:xcity-idol release')->everyMinute();
        $schedule->command('jav:xcity-idol daily')->dailyAt('12:00');
        // Update sub pages
        $schedule->command('jav:xcity-idol sub-pages')->weekly();
        // Update pages count
        $schedule->command('jav:xcity-idol pages-count')->weekly();
        $schedule->command('jav:xcity-idol item');

        // XCity Video
        $schedule->command('jav:xcity-video release')->everyMinute();
        $schedule->command('jav:xcity-video daily')->dailyAt('12:00');
        $schedule->command('jav:xcity-video item');

//        // Flickr
        $schedule->command('flickr:contacts')->weekly();
        $schedule->command('flickr:people info');
        $schedule->command('flickr:people photos');
        $schedule->command('flickr:people favorites');
        $schedule->command('flickr:photosets list');
        $schedule->command('flickr:photosets photos');
        $schedule->command('flickr:photo sizes');
//
//        // WordPress
//        $schedule->command('jav:email-wordpress');

//        // Core
        $schedule->command('core:cleanup')->mondays();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load([
            __DIR__.'/Commands',
            __DIR__.'/../../Jav/Console/Commands',
            __DIR__ . '/../../Flickr/Console/Commands',
        ]);

        require base_path('routes/console.php');
    }
}
