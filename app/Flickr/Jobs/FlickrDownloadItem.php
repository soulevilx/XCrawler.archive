<?php

namespace App\Flickr\Jobs;

use App\Core\Jobs\BaseJob;
use App\Core\Models\State;
use App\Core\Services\SftpService;
use App\Flickr\Models\FlickrDownloadItem as FlickrDownloadItemModel;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class FlickrDownloadItem extends BaseJob
{
    public function __construct(public FlickrDownloadItemModel $downloadItem)
    {
    }

    public function handle(SftpService $service)
    {
        $this->downloadItem->setState(State::STATE_PROCESSING);
        $photo = $this->downloadItem->photo;
        $download = $this->downloadItem->download;

        $downloadUrl = $photo->getLargestSizeUrl();

        $storage = Storage::drive('downloads');

        $saveToDir = 'Flickr/'.$download->path;
        $saveToFile = $storage->path($saveToDir).'/'.trim(basename($downloadUrl), '/');

        if (!$storage->exists($saveToDir)) {
            $storage->createDir($saveToDir);
        }

        $file = fopen($saveToFile, 'wb');

        if (!app()->environment('testing')) {
            $client = app(Client::class);
            $client->request('GET', $downloadUrl, ['sink' => $file]);
        }

        if ($service->put($saveToDir, $saveToFile)) {
            unlink($saveToFile);
            $this->downloadItem->setState(State::STATE_COMPLETED);
        }
    }
}
