<?php

namespace App\Flickr\Console\Commands;

use App\Core\Models\State;
use App\Flickr\Jobs\FlickrPeopleInfo;
use App\Flickr\Models\FlickrContactProcess;
use Illuminate\Console\Command;
use App\Flickr\Jobs\FlickrPeoplePhotos as FlickrPeoplePhotosJob;

class FlickrPeople extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flickr:people {task}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get people data';

    public function handle()
    {
        switch ($this->argument('task')) {
            case 'info':
                $this->peopleInfo();
                break;
            case 'photos':
                $this->peoplePhotos();
                break;
        }
    }

    protected function peopleInfo()
    {
        $contactProcess = FlickrContactProcess::byState(State::STATE_INIT)
            ->where('step', FlickrContactProcess::STEP_PEOPLE_INFO)
            ->first();

        if (!$contactProcess) {
            return;
        }

        FlickrPeopleInfo::dispatch($contactProcess)->onQueue('api');
    }

    public function peoplePhotos()
    {
        $contactProcess = FlickrContactProcess::byState(State::STATE_COMPLETED)
            ->where('step', FlickrContactProcess::STEP_PEOPLE_INFO)
            ->first();

        if (!$contactProcess) {
            return;
        }

        FlickrPeoplePhotosJob::dispatch($contactProcess)->onQueue('api');
    }
}
