<?php

namespace App\Core\Console\Commands;

use App\Core\Services\CleanupService;
use Illuminate\Console\Command;

class Cleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'core:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup';

    public function handle(CleanupService $service)
    {
        $service->cleanup();
    }
}
