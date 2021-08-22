<?php

namespace App\Jav\Console\Commands;

use App\Jav\Jobs\Onejav\DailyFetch;
use App\Jav\Jobs\Onejav\ReleaseFetch;
use Illuminate\Console\Command;

class Onejav extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jav:onejav {task}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching Onejav';

    public function handle()
    {
        switch ($this->input->getArgument('task')) {
            case 'daily':
                DailyFetch::dispatch()->onQueue('crawling');
                break;
            case 'release':
                ReleaseFetch::dispatch()->onQueue('crawling');
                break;
        }
    }
}
