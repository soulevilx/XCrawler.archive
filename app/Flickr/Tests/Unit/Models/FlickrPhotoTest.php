<?php

namespace App\Flickr\Tests\Unit\Models;

use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrPhoto;
use App\Flickr\Tests\FlickrTestCase;

class FlickrPhotoTest extends FlickrTestCase
{
    public function testAlbum()
    {
        $contact = FlickrContact::factory()->create([
            'nsid' => $this->faker->uuid,
        ]);
        $album = FlickrAlbum::factory()->create([
            'owner' => $contact->nsid,

        ]);

        $photos = FlickrPhoto::factory()->count(10)->create([
            'owner' => $contact->nsid,
        ]);

        $photos->each(function ($photo) use ($album) {
            /**
             * @var FlickrPhoto $photo
             */
            $album->photos()->syncWithoutDetaching($photo->id);
            $photo->refresh();
            $album->refresh();

            $this->assertTrue($photo->albums()->latest()->first()->is($album));
        });
    }
}
