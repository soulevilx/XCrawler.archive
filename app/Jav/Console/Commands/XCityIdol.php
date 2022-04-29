<?php

namespace App\Jav\Console\Commands;

use App\Jav\Jobs\XCity\Idol\FetchIdol;
use App\Jav\Jobs\XCity\Idol\UpdatePagesCount;
use App\Jav\Jobs\XCity\Idol\UpdateSubPages;
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
                    FetchIdol::dispatch($model)->onQueue('crawling');
                }
                break;

            // Update sub pages
            case 'sub-pages':
                UpdateSubPages::dispatch()->onQueue(XCityIdolService::QUEUE_NAME);
                break;
            // Update pages count for each kana
            case 'pages-count':
                $subPages = $service->getSubPages();
                foreach ($subPages as $subPage) {
                    $kana = str_replace('/idol/?kana=', '', $subPage);
                    UpdatePagesCount::dispatch($kana)->onQueue(XCityIdolService::QUEUE_NAME);
                }

                break;
        }
    }
}
