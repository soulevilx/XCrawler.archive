<?php

namespace App\Jav\Console\Commands;

use App\Core\Models\State;
use App\Jav\Jobs\R18\DailyFetch;
use App\Jav\Jobs\R18\ItemFetch;
use App\Jav\Jobs\R18\ReleaseFetch;
use App\Jav\Models\R18 as R18Model;
use Illuminate\Console\Command;

class R18 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jav:r18 {task}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching R18';

    public function handle()
    {
        switch ($this->input->getArgument('task')) {
            case 'release':
                ReleaseFetch::dispatch()->onQueue('crawling');

                break;

            case 'daily':
                DailyFetch::dispatch()->onQueue('crawling');

                break;

            case 'item':
                $model = R18Model::byState(State::STATE_INIT)->first();
                if ($model) {
                    ItemFetch::dispatch($model)->onQueue('crawling');
                }

                break;
        }
    }
}
