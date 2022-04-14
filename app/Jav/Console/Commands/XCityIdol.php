<?php

namespace App\Jav\Console\Commands;

use App\Jav\Jobs\XCity\IdolItemFetch;
use App\Jav\Services\XCityIdolService;
use Illuminate\Console\Command;

class XCityIdol extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jav:xcity-idol {task} {--id=} {--limit=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching XCity idol';

    public function handle(XCityIdolService $service)
    {
        switch ($this->input->getArgument('task')) {
            case 'release':
                $service->release();

                break;
            case 'daily':
                $service->daily();

                break;
            case 'item':
                $models = app(XCityIdolService::class)->getItems(
                    $this->input->getOption('limit'),
                    $this->input->getOption('id')
                );

                foreach ($models as $model) {
                    IdolItemFetch::dispatch($model)->onQueue('crawling');
                }
                break;
        }
    }
}
