<?php

namespace App\Flickr\Console\Commands\Download;

use App\Core\Models\State;
use App\Flickr\Jobs\Download\FlickrDownloadItem as FlickrDownloadItemJob;
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
        if ($this->argument('task') === 'downloadItem') {
            $this->downloadItem();
        }

        $this->output->title('Flickr download');
        $this->output->text('Getting user ...');

        switch ($this->argument('task')) {
            case 'album':
                $this->album();
                break;
        }
    }

    protected function album()
    {
        $this->output->text('Getting album ...');
        $album = $this->service->downloadAlbum($this->option('url'));

        FlickrRequestDownloadAlbum::dispatch(
            $album->getAlbumId(),
            $album->getUserNsid(),
        )->onQueue('api');
        $this->output->text('Pushed to queue');
    }

    protected function downloadItem()
    {
        if (!$downloadItem = FlickrDownloadItem::byState(State::STATE_INIT)->first()) {
            return;
        }

        FlickrDownloadItemJob::dispatch($downloadItem)->onQueue('api');
    }
}
