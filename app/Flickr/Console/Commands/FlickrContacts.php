<?php

namespace App\Flickr\Console\Commands;

use App\Flickr\Jobs\FlickrContacts as FlickrContactsJob;

class FlickrContacts extends AbstractBaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flickr:contacts {task=contacts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all contacts from authorized user';

    public function flickrContacts(): bool
    {
        FlickrContactsJob::dispatch()->onQueue('api');

        return true;
    }
}
