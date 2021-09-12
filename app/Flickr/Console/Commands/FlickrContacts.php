<?php

namespace App\Flickr\Console\Commands;

use App\Flickr\Jobs\FlickrContacts as FlickrContactsJob;
use Illuminate\Console\Command;

class FlickrContacts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flickr:contacts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all contacts from authorized user';

    public function handle()
    {
        $this->output->title('Flickr contacts');
        FlickrContactsJob::dispatch()->onQueue('api');
    }
}
