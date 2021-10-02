<?php

namespace App\Flickr\Tests\Unit\Jobs;

use App\Core\Models\State;
use App\Flickr\Events\FlickrDownloadCompleted;
use App\Flickr\Jobs\FlickrRequestDownloadAlbum;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrDownload;
use App\Flickr\Tests\FlickrTestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;

class FlickrRequestDownloadAlbumTest extends FlickrTestCase
{
    /**
     * This is completely test for download album process
     */
    public function testJob()
    {
        Event::fake([FlickrDownloadCompleted::class]);

        Storage::fake();
        $mocker = \Mockery::mock(Client::class);
        $mocker
            ->shouldReceive('request')
            ->andReturn(new Response);

            app()->instance(Client::class, $mocker);

        $albumId = 72157719703391487;
        $nsid = '51838687@N07';

        FlickrRequestDownloadAlbum::dispatch($albumId, $nsid);

        // Step 1: Make sure this contact is created. Because it's linked with Album
        $this->assertDatabaseHas('flickr_contacts', [
            'nsid' => $nsid,
        ]);

        // Step 2: Make sure album is created
        $this->assertDatabaseHas('flickr_albums', [
            'id' => 72157719703391487,
            'owner' => $nsid,
        ]);

        $album = FlickrAlbum::find($albumId);
        // Step 4: And now download request will be created
        $this->assertDatabaseHas('flickr_downloads', [
            'name' => $album->title,
            'model_id' => $album->id,
            'model_type' => FlickrAlbum::class,
            'total' => $album->photos
        ]);

        // Step 5: Get all photos of photoset
        $this->assertEquals($album->photos()->count(), $album->photos);
        /**
         * @var FlickrDownload $download
         */
        $download = FlickrDownload::where(
            [
                'model_id' => $album->id,
                'model_type' => FlickrAlbum::class,
            ]
        )->first();
        $this->assertTrue($download->model->is($album));

        foreach ($album->photos()->cursor() as $photo) {
            $this->assertDatabaseHas('flickr_download_items', [
                'photo_id' => $photo->id,
                'state_code' => State::STATE_INIT,
                'download_id' => $download->id,
            ]);
        }

        for ($count = 0; $count <= $download->items()->count(); $count++) {
            $this->artisan('flickr:download downloadItem');
        }

        Event::assertDispatched(FlickrDownloadCompleted::class, function ($event) use ($download) {
            return $event->download->is($download);
        });
    }
}
