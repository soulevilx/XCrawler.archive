<?php

namespace App\Jav\Console\Commands;

use App\Core\Models\State;
use App\Jav\Jobs\XCity\IdolItemFetch;
use App\Jav\Jobs\XCity\VideoItemFetch;
use App\Jav\Services\XCityVideoService;
use Illuminate\Console\Command;

class XCityVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jav:xcity-video {task}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching XCity videos';

    public function handle(XCityVideoService $service)
    {
        switch ($this->input->getArgument('task')) {
            case 'release':
                $service->release();

                break;

            case 'daily':
                $service->daily();

                break;

            case 'item':
                if ($model = \App\Jav\Models\XCityVideo::byState(State::STATE_INIT)->first()) {

                    VideoItemFetch::dispatch($model)->onQueue('crawling');
                }

                break;
        }
    }
}
