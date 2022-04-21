<?php

namespace App\Jav\Console\Commands;

use App\Jav\Jobs\Onejav\DailyFetch;
use App\Jav\Jobs\Onejav\ReleaseFetch;
use App\Jav\Services\OnejavService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

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
        $task = $this->input->getArgument('task');
        switch ($task) {
            case 'daily':
                DailyFetch::dispatch()
                    ->onQueue(OnejavService::QUEUE_NAME);
                break;
            case 'release':
                ReleaseFetch::dispatch()
                    ->onQueue(OnejavService::QUEUE_NAME);
                break;
        }

        $this->output->info('Pushed '.Str::camel($task).' to queue');
    }
}
