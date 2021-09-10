<?php

namespace App\Flickr\Console\Commands;

use App\Flickr\Jobs\FlickrPeopleInfo;
use App\Flickr\Jobs\FlickrPeoplePhotos as FlickrPeoplePhotosJob;
use App\Flickr\Models\FlickrContactProcess;

class FlickrPeople extends BaseProcessCommand
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
        /**
         * Whenever contact is created it'll create process STEP_PEOPLE_INFO
         * This process will fetch detail people information
         */
        $process = $this->getProcessItem(FlickrContactProcess::STEP_PEOPLE_INFO);
        FlickrPeopleInfo::dispatch($process)->onQueue('api');
    }

    public function peoplePhotos()
    {
        /**
         * After STEP_PEOPLE_INFO completed will create STEP_PEOPLE_PHOTOS
         * This process will fetch all photos of an contact
         */
        $process = $this->getProcessItem(FlickrContactProcess::STEP_PEOPLE_PHOTOS);
        FlickrPeoplePhotosJob::dispatch($process)->onQueue('api');
    }
}
