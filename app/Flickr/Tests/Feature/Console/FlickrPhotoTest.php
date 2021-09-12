<?php

namespace App\Flickr\Tests\Feature\Console;

use App\Flickr\Jobs\FlickrPhotoSizes;
use App\Flickr\Models\FlickrPhoto;
use App\Flickr\Tests\FlickrTestCase;
use Illuminate\Support\Facades\Queue;

class FlickrPhotoTest extends FlickrTestCase
{
    public function testSizes()
    {
        Queue::fake();
        $photo = FlickrPhoto::factory()->create();

        $this->artisan('flickr:photo sizes');
        Queue::assertPushed(FlickrPhotoSizes::class, function ($job) use ($photo) {
            return $job->photo->is($photo);
        });
    }

    public function testSizesWhenNoPhoto()
    {
        Queue::fake();

        $this->artisan('flickr:photo sizes');
        Queue::assertNotPushed(FlickrPhotoSizes::class);
    }
}
