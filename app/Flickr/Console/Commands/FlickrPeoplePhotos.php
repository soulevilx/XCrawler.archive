<?php

namespace App\Flickr\Console\Commands;

use App\Core\Models\State;
use App\Flickr\Jobs\FlickrPeopleInfo;
use App\Flickr\Models\FlickrContactProcess;
use Illuminate\Console\Command;

class FlickrPeoplePhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flickr:people-photo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get people data';

    public function handle()
    {
        $contactProcess = FlickrContactProcess::byState(State::STATE_INIT)
            ->where('step', FlickrContactProcess::STEP_PEOPLE_INFO)
            ->first();

        if (!$contactProcess) {
            return;
        }

        FLickrPeopleInfo::dispatch($contactProcess)->onQueue('api');
    }
}
