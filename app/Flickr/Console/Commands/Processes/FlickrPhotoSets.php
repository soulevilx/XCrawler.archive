<?php

namespace App\Flickr\Console\Commands\Processes;

use App\Flickr\Jobs\FlickrPhotoSets as FlickrPhotoSetsJob;
use App\Flickr\Jobs\FlickrPhotoSetsPhotos;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrProcess;

/**
 * Step 2 & 3
 */
class FlickrPhotoSets extends BaseProcessCommand
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
    protected $description = 'Get photosets data';

    public function handle()
    {
        switch ($this->argument('task')) {
            case 'list':
                $this->photosetsGetList();
                break;
            case 'photos':
                $this->photosetsGetPhotos();
                break;
        }
    }

    /**
     * This step will be created right after contact is created
     */
    protected function photosetsGetList()
    {
        $process = $this->getProcessItem(FlickrProcess::STEP_PHOTOSETS_LIST);
        FlickrPhotoSetsJob::dispatch($process)->onQueue('api');
    }

    /**
     * Step 3
     * This step will be created right after album is created
     */
    protected function photosetsGetPhotos()
    {
        $process = $this->getProcessItem(
            FlickrProcess::STEP_PHOTOSETS_PHOTOS,
            FlickrAlbum::class
        );
        FlickrPhotoSetsPhotos::dispatch($process)->onQueue('api');
    }
}
