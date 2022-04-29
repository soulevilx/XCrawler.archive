<?php

namespace App\Jav\Console\Commands;

use App\Jav\Jobs\SCute\ItemFetch;
use App\Jav\Jobs\SCute\ReleaseFetch;
use App\Jav\Services\SCuteService;
use Illuminate\Console\Command;

class SCute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jav:scute {task} {--id=} {--limit=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching SCute';

    public function handle()
    {
        $task = $this->input->getArgument('task');
        switch ($task) {
            case 'release':
                ReleaseFetch::dispatch()->onQueue('crawling');
                break;
            case 'item':
                $models = app(SCuteService::class)->getItems(
                    $this->input->getOption('limit'),
                    $this->input->getOption('id')
                );

                foreach ($models as $model) {
                    ItemFetch::dispatch($model)->onQueue('crawling');
                }
        }
    }
}
