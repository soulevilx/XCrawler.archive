<?php

namespace App\Flickr\Console\Commands;

use App\Flickr\Jobs\FlickrFavorites;
use App\Flickr\Jobs\FlickrPeopleInfo;
use App\Flickr\Jobs\FlickrPeoplePhotos as FlickrPeoplePhotosJob;
use App\Flickr\Models\FlickrProcess;

/**
 * Step 1
 * - Getting people information
 * - Getting people' photos
 */
class FlickrPeople extends AbstractFlickrCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flickr:people {task} {--limit=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get people data. Tasks: info || photos';

    public function flickrInfo(): bool
    {
        /**
         * Whenever contact is created it'll create process STEP_PEOPLE_INFO
         * This process will fetch detail people information
         */
        $this->getProcessItem(FlickrProcess::STEP_PEOPLE_INFO)
            ->each(function ($process) {
                FlickrPeopleInfo::dispatch($process)->onQueue('api');
            });

        return true;
    }

    public function flickrPhotos()
    {
        /**
         * After STEP_PEOPLE_INFO completed will create STEP_PEOPLE_PHOTOS
         * This process will fetch all photos of an contact
         */
        $this->getProcessItem(FlickrProcess::STEP_PEOPLE_PHOTOS)
            ->each(function ($process) {
                FlickrPeoplePhotosJob::dispatch($process)->onQueue('api');
            });

        return true;
    }

    public function flickrFavorites()
    {
        $this->getProcessItem(FlickrProcess::STEP_PEOPLE_FAVORITE_PHOTOS)
            ->each(function ($process) {
                FlickrFavorites::dispatch($process)->onQueue('api');
            });

        return true;
    }
}
