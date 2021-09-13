<?php

namespace App\Flickr\Tests\Unit\Jobs;

use App\Flickr\Jobs\FlickrPhotoSetsPhotos;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Tests\FlickrTestCase;

class FlickrPhotoSetsPhotosTest extends FlickrTestCase
{
    public function testJob()
    {
        $album = FlickrAlbum::factory()->create();
        $process = FlickrProcess::factory()->create([
            'model_id' => $album->id,
            'model_type' => FlickrAlbum::class,
            'step' => FlickrProcess::STEP_PHOTOSETS_PHOTOS,
        ]);

        FlickrPhotoSetsPhotos::dispatch($process);
        $album->refresh();

        $this->assertEquals(55, $album->photos()->count());
        $this->assertDatabaseCount('flickr_photos', 55);
    }
}
