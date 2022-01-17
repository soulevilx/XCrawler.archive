<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Models\FlickrDownloadItem as FlickrDownloadItemModel;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class FlickrDownloadItem extends AbstractLimitJob
{
    public function __construct(public FlickrDownloadItemModel $downloadItem)
    {
    }

    public function handle()
    {
        $this->downloadItem->setState(State::STATE_PROCESSING);
        $photo = $this->downloadItem->photo;
        $download = $this->downloadItem->download;

        $url = $photo->getLargestSizeUrl();

        $dir = '/Flickr/' . $download->path;
        $storage = Storage::drive('downloads');

        if (!$storage->exists($dir)) {
            $storage->createDir($dir);
        }
        $file = fopen($storage->path($dir) . '/' . basename($url), 'wb');

        if (!app()->environment('testing')) {
            $client = app(Client::class);
            $client->request('GET', $url, ['sink' => $file]);
        }

        $this->downloadItem->setState(State::STATE_COMPLETED);
    }
}
