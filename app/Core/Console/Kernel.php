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
