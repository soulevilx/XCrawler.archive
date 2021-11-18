<?php

namespace App\Flickr\Tests\Unit\Jobs;

use App\Core\Models\State;
use App\Flickr\Jobs\FlickrPhotoSetsPhotos;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Tests\FlickrTestCase;

class FlickrPhotoSetsPhotosTest extends FlickrTestCase
{
    public function testJob()
    {
        $album = FlickrAlbum::factory()->create([
            'id' => '72157688392979533',
            'owner' => '94529704@N02',
        ]);
        $process = FlickrProcess::factory()->create([
            'model_id' => $album->id,
            'model_type' => FlickrAlbum::class,
            'step' => FlickrProcess::STEP_PHOTOSETS_PHOTOS,
        ]);

        FlickrPhotoSetsPhotos::dispatch($process);
        $album->refresh();

        $this->assertEquals(98, $album->photos()->count());
        $this->assertDatabaseCount('flickr_photos', 98);
    }

    public function testJobAlbumDeleted()
    {
        $contact = FlickrContact::factory()->create([
            'nsid' => 'deleted',
        ]);
        $album = FlickrAlbum::factory()->create([
            'id' => 999,
            'owner' => $contact->nsid,
        ]);

        $process = FlickrProcess::factory()->create([
            'model_id' => $album->id,
            'model_type' => FlickrAlbum::class,
            'step' => FlickrProcess::STEP_PHOTOSETS_PHOTOS,
        ]);

        FlickrPhotoSetsPhotos::dispatch($process);

        $this->assertEquals(State::STATE_FAILED, $process->refresh()->state_code);
    }
}
