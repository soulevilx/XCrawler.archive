<?php

namespace App\Flickr\Console\Commands;

use App\Flickr\Jobs\FlickrPhotoSets as FlickrPhotoSetsJob;
use App\Flickr\Jobs\FlickrPhotoSetsPhotos;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrContactProcess;

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
    protected $description = 'Get people data';

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

    protected function photosetsGetList()
    {
        $process = $this->getProcessItem(FlickrContactProcess::STEP_PHOTOSETS_LIST);
        FlickrPhotoSetsJob::dispatch($process)->onQueue('api');
    }

    protected function photosetsGetPhotos()
    {
        $process = $this->getProcessItem(
            FlickrContactProcess::STEP_PHOTOSETS_PHOTOS,
            FlickrAlbum::class
        );
        FlickrPhotoSetsPhotos::dispatch($process)->onQueue('api');
    }
}
