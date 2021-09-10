<?php

namespace App\Flickr\Tests\Unit\Jobs;

use App\Core\Models\State;
use App\Flickr\Jobs\FlickrContacts;
use App\Flickr\Jobs\FlickrPeopleInfo;
use App\Flickr\Jobs\FlickrPeoplePhotos;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrContactProcess;
use App\Flickr\Models\FlickrPhoto;
use App\Flickr\Tests\FlickrTestCase;

class FlickrPeoplePhotosTest extends FlickrTestCase
{
    public function testJob()
    {
        $contactProcess = FlickrContactProcess::factory()->create([
            'step' => FlickrContactProcess::STEP_PEOPLE_INFO,
            'state_code' => State::STATE_COMPLETED,
        ]);

        FlickrPeoplePhotos::dispatch($contactProcess);

        $contactProcess->refresh();
        $contactProcess->model->refresh();
        $this->assertEquals(State::STATE_COMPLETED, $contactProcess->state_code);
        $this->assertEquals(358, FlickrPhoto::where('owner', $contactProcess->model->nsid)->count());
        $this->assertDatabaseHas('flickr_contact_processes', [
            'model_id' => $contactProcess->model->id,
            'model_type' => FlickrContact::class,
            'step' => FlickrContactProcess::STEP_PHOTOSETS_LIST,
            'state_code' => State::STATE_INIT,
        ]);
        $this->assertDatabaseCount('flickr_photos', 358);
    }
}
