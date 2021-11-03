<?php

namespace App\Flickr\Tests\Unit\Models;

use App\Core\Models\State;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrPhoto;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Tests\FlickrTestCase;

class FlickrAlbumTest extends FlickrTestCase
{
    public function testContact()
    {
        $contact = FlickrContact::factory()->create([
            'nsid' => $this->faker->uuid,
        ]);
        $album = FlickrAlbum::factory()->create([
            'owner' => $contact->nsid,
        ]);

        $this->assertTrue($album->contact->is($contact));
    }

    public function testProcess()
    {
        $album = FlickrAlbum::factory()->create();
        $this->assertDatabaseHas('flickr_contact_processes', [
            'model_id' => $album->id,
            'model_type' => FlickrAlbum::class,
            'step' => FlickrProcess::STEP_PHOTOSETS_PHOTOS,
            'state_code' => State::STATE_INIT,
        ]);
    }

    public function testPhotos()
    {
        $photo = FlickrPhoto::factory()->create();
        /**
         * @var FlickrAlbum $album
         */
        $album = FlickrAlbum::factory()->create();
        $album->photos()->syncWithoutDetaching([$photo->id]);

        $this->assertDatabaseHas('flickr_album_photos', [
            'photo_id' => $photo->id,
            'album_id' => $album->id,
        ]);
    }
}
