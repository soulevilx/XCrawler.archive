<?php

namespace App\Flickr\Console\Commands;

use App\Flickr\Jobs\FlickrPhotoSizes;
use App\Flickr\Models\FlickrPhoto as FlickrPhotoModel;

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
        FlickrPhotoModel::whereNull('sizes')
            ->limit($this->option('limit'))
            ->get()
            ->each(function ($photo) {
                FlickrPhotoSizes::dispatch($photo)->onQueue('api');
            });
    }
}
