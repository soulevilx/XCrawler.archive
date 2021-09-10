<?php

namespace App\Flickr\Console\Commands\Download;

use App\Flickr\Jobs\FlickrContacts as FlickrContactsJob;
use App\Flickr\Jobs\FlickrRequestDownloadAlbum;
use Illuminate\Console\Command;

class FlickrDownload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flickr:download {task} {--albumid=} {--nsid=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all contacts from authorized user';

    public function handle()
    {
        $this->output->title('Flickr download');
        FlickrRequestDownloadAlbum::dispatch((int) $this->option('albumid'), $this->option('nsid'))->onQueue('api');
    }
}
