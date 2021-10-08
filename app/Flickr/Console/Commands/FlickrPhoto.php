<?php

namespace App\Flickr\Console\Commands;

use App\Flickr\Jobs\FlickrPhotoSizes;
use App\Flickr\Models\FlickrPhoto as FlickrPhotoModel;
use Illuminate\Console\Command;

class FlickrPhoto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flickr:photo {task}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get photo data. Tasks: sizes';

    public function handle()
    {
        switch ($this->argument('task')) {
            case 'sizes':
                $this->photoGetSizes();
                break;
        }
    }

    protected function photoGetSizes()
    {
        if (!$photo = FlickrPhotoModel::whereNull('sizes')->first()) {
            return;
        }

        FlickrPhotoSizes::dispatch($photo)->onQueue('api');
    }
}
