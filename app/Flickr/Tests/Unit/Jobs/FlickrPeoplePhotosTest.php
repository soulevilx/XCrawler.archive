<?php

namespace App\Flickr\Tests\Unit\Jobs;

use App\Core\Models\State;
use App\Flickr\Exceptions\UserDeleted;
use App\Flickr\Jobs\FlickrPeoplePhotos;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrPhoto;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Tests\FlickrTestCase;

class FlickrPeoplePhotosTest extends FlickrTestCase
{
    public function testJob()
    {
        $contactProcess = FlickrProcess::factory()->create([
            'step' => FlickrProcess::STEP_PEOPLE_INFO,
            'state_code' => State::STATE_COMPLETED,
        ]);

        FlickrPeoplePhotos::dispatch($contactProcess);

        $contactProcess->refresh();
        $contactProcess->model->refresh();
        $this->assertEquals(State::STATE_COMPLETED, $contactProcess->state_code);
        $this->assertEquals(358, FlickrPhoto::where('owner', $contactProcess->model->nsid)->count());
        $this->assertDatabaseHas('flickr_processes', [
            'model_id' => $contactProcess->model->id,
            'model_type' => FlickrContact::class,
            'step' => FlickrProcess::STEP_PHOTOSETS_LIST,
            'state_code' => State::STATE_INIT,
        ]);
        $this->assertDatabaseCount('flickr_photos', 358);

        // Execute job again will not create duplicate
        FlickrPeoplePhotos::dispatch($contactProcess);
        $this->assertDatabaseCount('flickr_photos', 358);
    }

    public function testJobWithDeletedUser()
    {
        $contact = FlickrContact::factory()->create(['nsid' => 'deleted']);

        $contactProcess = FlickrProcess::factory()->create([
            'model_id' => $contact->id,
            'model_type' => $contact->getMorphClass(),
            'step' => FlickrProcess::STEP_PEOPLE_INFO,
            'state_code' => State::STATE_COMPLETED,
        ]);

        FlickrPeoplePhotos::dispatch($contactProcess);
        $this->assertSoftDeleted($contact);
    }
}
