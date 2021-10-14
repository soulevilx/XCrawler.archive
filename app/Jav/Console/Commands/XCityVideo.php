<?php

namespace App\Jav\Console\Commands;

use App\Core\Models\State;
use App\Jav\Jobs\XCity\VideoItemFetch;
use App\Jav\Models\XCityVideo as XCityVideoModel;
use App\Jav\Services\XCityVideoService;
use Illuminate\Console\Command;

class XCityVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jav:xcity-video {task} {--id=} {--limit=5}';

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
                $query = XCityVideoModel::byState(State::STATE_INIT);
                if ($limit = $this->input->getOption('limit')) {
                    $query = $query->limit($limit);
                } elseif ($id = $this->input->getOption('id')) {
                    $query = $query->where('id', $id);
                }

                foreach ($query->cursor() as $model) {
                    VideoItemFetch::dispatch($model)->onQueue('crawling');
                }

                break;
        }
    }
}
