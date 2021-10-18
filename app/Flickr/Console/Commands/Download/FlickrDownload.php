<?php

namespace App\Flickr\Console\Commands\Download;

use App\Core\Models\State;
use App\Flickr\Jobs\FlickrDownloadItem as FlickrDownloadItemJob;
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

    public function handle(FlickrService $service)
    {
        $this->service = $service;

        switch ($this->argument('task')) {
            case 'album':
                $this->album();
                break;
            case 'downloadItem':
                $this->downloadItem();
                break;
        }
    }

    protected function album()
    {
        $this->output->text('Getting album ...');
        $album = $this->service->downloadAlbum($this->option('url'));
        $this->output->text('Album ID: ' . $album->getAlbumId());
        $this->output->text('Owner: ' . $album->getUserNsid());

        $this->output->text('Pushed to queue');
    }

    protected function downloadItem()
    {
        if (!$downloadItem = FlickrDownloadItem::byState(State::STATE_INIT)->first()) {
            return;
        }

        $this->table(
            [
                'download_id',
                'photo_id',
            ],
            [
                [
                    $downloadItem->download_id,
                    $downloadItem->photo_id,
                ],
            ]
        );
        FlickrDownloadItemJob::dispatch($downloadItem)->onQueue('api');
    }
}
