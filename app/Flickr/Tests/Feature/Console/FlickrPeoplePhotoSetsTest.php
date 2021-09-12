<?php

namespace App\Flickr\Tests\Feature\Console;

use App\Core\Models\State;
use App\Flickr\Jobs\FlickrPhotoSets;
use App\Flickr\Jobs\FlickrPhotoSetsPhotos;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrProcess;
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
            return $job->process->model->is($contact);
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

    public function testPhotosWhenNoProcess()
    {
        Queue::fake();
        $contact = FlickrContact::factory()->create();
        $album = FlickrAlbum::factory()->create([
            'owner' => $contact->nsid,
        ]);
        $album->process()->delete();
        $this->artisan('flickr:photosets photos');

        $this->assertDatabaseHas('flickr_contact_processes', [
            'step' => FlickrProcess::STEP_PHOTOSETS_PHOTOS,
            'state_code' => State::STATE_INIT,
            'deleted_at' => null,
            'model_id' => $album->id,
            'model_type' => FlickrAlbum::class,
        ]);

        Queue::assertPushed(FlickrPhotoSetsPhotos::class, function ($job) use ($album) {
            return $job->process->model->is($album);
        });
    }
}
