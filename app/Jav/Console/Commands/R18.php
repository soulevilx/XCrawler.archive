<?php

namespace App\Jav\Console\Commands;

use App\Core\Services\Facades\Application;
use App\Jav\Jobs\R18\DailyFetch;
use App\Jav\Jobs\R18\ItemFetch;
use App\Jav\Jobs\R18\ReleaseFetch;
use App\Jav\Services\R18Service;
use Illuminate\Console\Command;

class R18 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jav:r18 {task} {--id=} {--limit=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching R18';

    public function handle()
    {
        $task = $this->input->getArgument('task');
        switch ($task) {
            case 'release':
            case 'daily':
                foreach (Application::getArray(R18Service::SERVICE_NAME, 'urls') as $key => $url) {
                    if ($task === 'release') {
                        ReleaseFetch::dispatch($url, $key)->onQueue(R18Service::QUEUE_NAME);
                    } else {
                        DailyFetch::dispatch($url)->onQueue(R18Service::QUEUE_NAME);
                    }

                    $this->output->info(sprintf('Pushed %s %s to queue', $task, $url));
                }

                break;
            case 'item':
                $models = app(R18Service::class)->getItems(
                    $this->input->getOption('limit'),
                    $this->input->getOption('id')
                );

                foreach ($models as $model) {
                    ItemFetch::dispatch($model)->onQueue(R18Service::QUEUE_NAME);
                }

                break;
        }
    }
}
