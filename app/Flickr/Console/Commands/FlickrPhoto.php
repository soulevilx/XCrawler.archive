<?php

namespace App\Flickr\Console\Commands;

use App\Flickr\Jobs\FlickrPhotoSizes;
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
    protected $description = 'Get people data';

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
        $photo = \App\Flickr\Models\FlickrPhoto::whereNull('sizes')->first();
        if (!$photo) {
            return;
        }

        FlickrPhotoSizes::dispatch($photo)->onQueue('api');
    }
}
