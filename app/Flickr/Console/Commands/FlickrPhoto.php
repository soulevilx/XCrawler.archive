<?php

namespace App\Flickr\Console\Commands;

use App\Core\Services\Facades\Application;
use App\Flickr\Jobs\FlickrPhotoSizes;
use App\Flickr\Repositories\PhotoRepository;

class FlickrPhoto extends AbstractFlickrCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flickr:photo {task} {--limit=15}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get photo data. Tasks: sizes';

    protected function flickrSizes()
    {
        app(PhotoRepository::class)
            ->getUnsizedItems(Application::getSetting('flickr', 'limit_process_items', 2))
            ->each(function ($photo) {
                FlickrPhotoSizes::dispatch($photo)->onQueue('api');
                $this->output->text($photo->id);
            });

        return true;
    }
}
