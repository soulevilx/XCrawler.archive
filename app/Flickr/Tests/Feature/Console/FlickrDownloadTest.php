<?php

namespace App\Flickr\Tests\Feature\Console;

use App\Flickr\Jobs\FlickrRequestDownloadAlbum;
use App\Flickr\Tests\FlickrTestCase;
use Illuminate\Support\Facades\Queue;

class FlickrDownloadTest extends FlickrTestCase
{
    public function testDownloadAlbum()
    {
        Queue::fake();
        $this->artisan('flickr:download album --url=https://www.flickr.com/photos/51838687@N07/albums/72157719703391487');

        Queue::assertPushed(FlickrRequestDownloadAlbum::class, function ($job) {
            return $job->albumId === 72157719703391487 && $job->nsid === '51838687@N07';
        });
    }

    public function testDownloadAlbums()
    {
        Queue::fake();
        $this->artisan('flickr:download albums --url=https://www.flickr.com/photos/soulevilx/albums/72157692139427840');

        Queue::assertPushed(FlickrRequestDownloadAlbum::class);
    }
}
