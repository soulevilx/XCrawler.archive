<?php

namespace App\Jav\Console\Commands;

use App\Jav\Services\XCityService;
use Illuminate\Console\Command;

class XCityIdol extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jav:xcity-idol {task}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching XCity idol';

    public function handle(XCityService $service)
    {
        switch ($this->input->getArgument('task')) {
            case 'release':
                $service->release();

                break;
            case 'daily':
                $service->daily();

                break;
        }
    }
}
