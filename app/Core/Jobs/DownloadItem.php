<?php

namespace App\Core\Jobs;

use App\Core\Models\State;
use App\Flickr\Models\FlickrDownloadItem;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use phpseclib3\Net\SFTP;

class DownloadItem extends BaseJob
{
    public function __construct(
        public string $saveTo,
        public string $downloadUrl,
        public FlickrDownloadItem $downloadItem
    ) {
    }

    public function handle()
    {
        $storage = Storage::drive('downloads');

        if (!$storage->exists($this->saveTo)) {
            $storage->createDir($this->saveTo);
        }

        $file = fopen($storage->path($this->saveTo).'/'.basename($this->downloadUrl), 'wb');

        if (!app()->environment('testing')) {
            $client = app(Client::class);
            $client->request('GET', $this->downloadUrl, ['sink' => $file]);
        }

        if (app()->environment('testing')) {
            $this->downloadItem->setState(State::STATE_COMPLETED);
            return;
        }

        $username = config('services.flickr.storage_server_username');
        $sftp = new SFTP(config('services.flickr.storage_server'), config('services.flickr.storage_server_port'));
        $sftp->login(config('services.flickr.storage_server_username'),
            config('services.flickr.storage_server_password'));

        $saveTo = '/home/'.$username.'/'.$this->saveTo;
        $sftp->mkdir($saveTo, -1, true);
        if ($sftp->put(
            $saveTo.'/'.basename($this->downloadUrl),
            $storage->path($this->saveTo).'/'.basename($this->downloadUrl),
            SFTP::SOURCE_LOCAL_FILE
        )
        ) {
            $this->downloadItem->setState(State::STATE_COMPLETED);
        }
    }
}
