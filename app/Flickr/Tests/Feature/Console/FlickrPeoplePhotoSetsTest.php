<?php

namespace App\Flickr\Tests\Feature\Console;

use App\Flickr\Jobs\FlickrPhotoSets;
use App\Flickr\Jobs\FlickrPhotoSetsPhotos;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Tests\FlickrTestCase;
use Illuminate\Support\Facades\Queue;

class FlickrPeoplePhotoSetsTest extends FlickrTestCase
{
    public function testList()
    {
        Queue::fake();
        $contact = FlickrContact::factory()->create();
        $this->artisan('flickr:photosets list');

        Queue::assertPushed(FlickrPhotoSets::class, function ($job) use ($contact) {
            return $job->contactProcess->model->is($contact);
        });
    }

    public function testPhotos()
    {
        Queue::fake();
        $contact = FlickrContact::factory()->create();
        $album = FlickrAlbum::factory()->create([
            'owner' => $contact->nsid,
        ]);
        $this->artisan('flickr:photosets photos');

        Queue::assertPushed(FlickrPhotoSetsPhotos::class, function ($job) use ($album) {
            return $job->process->model->is($album);
        });
    }
}
