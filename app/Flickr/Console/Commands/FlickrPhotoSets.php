<?php

namespace App\Flickr\Console\Commands;

use App\Flickr\Console\Commands\Traits\HasProcesses;
use App\Flickr\Jobs\FlickrPhotoSets as FlickrPhotoSetsJob;
use App\Flickr\Jobs\FlickrPhotoSetsPhotos;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrProcess;

/**
 * Step 2 & 3
 */
class FlickrPhotoSets extends AbstractBaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flickr:photosets {task}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get photosets data. Tasks: list || photos';

    /**
     * This step will be created right after contact is created
     */
    protected function flickrList(): bool
    {
        $this->getProcessItem(FlickrProcess::STEP_PHOTOSETS_LIST)->each(function ($process) {
            FlickrPhotoSetsJob::dispatch($process)->onQueue('api');
        });

        return true;
    }

    /**
     * Step 3
     * This step will be created right after album is created
     */
    protected function flickrPhotos(): bool
    {
        $this->getProcessItem(
            FlickrProcess::STEP_PHOTOSETS_PHOTOS,
            FlickrAlbum::class
        )->each(function ($process) {
            FlickrPhotoSetsPhotos::dispatch($process)->onQueue('api');
        });

        return true;
    }
}
