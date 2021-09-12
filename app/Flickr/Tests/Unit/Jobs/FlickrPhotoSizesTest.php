<?php

namespace App\Flickr\Tests\Unit\Jobs;

use App\Flickr\Jobs\FlickrPhotoSizes;
use App\Flickr\Models\FlickrPhoto;
use App\Flickr\Tests\FlickrTestCase;

class FlickrPhotoSizesTest extends FlickrTestCase
{
    public function testGetSizes()
    {
        $photo = FlickrPhoto::factory()->create(['id' => 50068037298]);

        FlickrPhotoSizes::dispatch($photo);

        $photo->refresh();
        $this->assertIsArray($photo->sizes);
        $this->assertIsArray($photo->sizes[0]);
    }
}
