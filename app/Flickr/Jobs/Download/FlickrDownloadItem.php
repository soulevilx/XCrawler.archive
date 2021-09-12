<?php

namespace App\Flickr\Jobs\Download;

use App\Core\Models\State;
use App\Flickr\Jobs\Traits\HasFlickrMiddleware;
use App\Flickr\Models\FlickrPhoto;
use App\Flickr\Services\FlickrService;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class FlickrDownloadItem implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use HasFlickrMiddleware;

    public function __construct(public \App\Flickr\Models\FlickrDownloadItem $downloadItem)
    {
    }

    public function handle(FlickrService $service)
    {
        $this->downloadItem->setState(State::STATE_PROCESSING);
        /**
         * @var FlickrPhoto $photo
         */
        $photo = $this->downloadItem->photo;
        $download = $this->downloadItem->download;

        if (!$photo->hasSizes()) {
            $sizes = $service->photos()->getSizes($photo->id);
            $photo->sizes = $sizes;
        }

        $url = $photo->largestSize()['source'];
        $dir = '/Flickr/' . $download->path;
        $storage = Storage::drive('downloads');

        if (!$storage->exists($dir)) {
            $storage->createDir($dir);
        }
        $file = fopen($storage->path($dir) . basename($url), 'wb');

        if (!app()->environment('testing')) {
            $client = app(Client::class);
            $client->request('GET', $url, ['sink' => $file]);
        }

        $this->downloadItem->setState(State::STATE_COMPLETED);
    }
}
