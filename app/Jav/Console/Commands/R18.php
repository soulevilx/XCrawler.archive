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
    protected $signature = 'jav:r18 {task} {--id=}';

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
                foreach (array_keys(R18Model::MOVIE_URLS) as $key) {
                    ReleaseFetch::dispatch($key)->onQueue('crawling');
                }

                break;

            case 'daily':
                DailyFetch::dispatch()->onQueue('crawling');

                break;

            case 'item':
                if ($id = $this->input->getOption('id')) {
                    $model = R18Model::find($id);
                } else {
                    $model = R18Model::byState(State::STATE_INIT)->first();
                }

                if ($model) {
                    ItemFetch::dispatch($model)->onQueue('crawling');
                }

                break;
            case 'cleanup':
                if ($model = R18Model::byState(State::STATE_PROCESSING)->first()) {
                    ItemFetch::dispatch($model)->onQueue('crawling');
                }

                break;
        }
    }
}
