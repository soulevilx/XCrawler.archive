<?php

namespace App\Flickr\Console\Commands\Download;

use App\Core\Models\State;
use App\Flickr\Jobs\FlickrContacts as FlickrContactsJob;
use App\Flickr\Jobs\FlickrRequestDownloadAlbum;
use App\Flickr\Models\FlickrDownloadItem;
use App\Flickr\Services\FlickrService;
use Illuminate\Console\Command;

class FlickrDownload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flickr:download {task} {--url=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all contacts from authorized user';

    protected FlickrService $service;
    protected array $user;

    public function handle(FlickrService $service)
    {
        $this->service = $service;
        if ($this->argument('task') !== 'downloadItem')
        {
            $this->output->title('Flickr download');
            $this->output->text('Getting user ...');
            $this->user = $this->service->urls()->lookupUser($this->option('url'));

            switch ($this->argument('task')) {
                case 'album':
                    $this->album();
                    break;
            }

            return;
        }

        $this->downloadItem();
    }

    protected function album()
    {
        $this->output->text('Getting album ...');
        $url = explode('/', $this->option('url'));
        $albumId = end($url);
        FlickrRequestDownloadAlbum::dispatch((int) $albumId, $this->user['id'])->onQueue('api');
    }

    protected function downloadItem()
    {
        $downloadItem = FlickrDownloadItem::byState(State::STATE_INIT)->first();
        if ($downloadItem) {
            \App\Flickr\Jobs\Download\FlickrDownloadItem::dispatch($downloadItem)->onQueue('api');
        }
    }
}
