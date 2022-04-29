<?php

namespace App\Flickr\Tests\Unit\Jobs;

use App\Core\Models\State;
use App\Flickr\Exceptions\FlickrGeneralException;
use App\Flickr\Jobs\FlickrPeoplePhotos;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrPhoto;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Tests\FlickrTestCase;

class FlickrPeoplePhotosTest extends FlickrTestCase
{
    public function testJob()
    {
        $process = FlickrProcess::factory()->create([
            'step' => FlickrProcess::STEP_PEOPLE_INFO,
            'state_code' => State::STATE_COMPLETED,
        ]);

        FlickrPeoplePhotos::dispatch($process);

        $process->refresh();
        $process->model->refresh();
        $this->assertEquals(State::STATE_COMPLETED, $process->state_code);
        $this->assertEquals(358, FlickrPhoto::where('owner', $process->model->nsid)->count());

        $this->assertDatabaseCount('flickr_photos', 358);

        // Execute job again will not create duplicate
        FlickrPeoplePhotos::dispatch($process);
        $this->assertDatabaseCount('flickr_photos', 358);
    }

    public function testJobWithDeletedUser()
    {
        try {
            $contact = FlickrContact::factory()->create(['nsid' => 'deleted']);
            $contactProcess = FlickrProcess::factory()->create([
                'model_id' => $contact->id,
                'model_type' => $contact->getMorphClass(),
                'step' => FlickrProcess::STEP_PEOPLE_INFO,
                'state_code' => State::STATE_COMPLETED,
            ]);
            FlickrPeoplePhotos::dispatch($contactProcess);
        } catch (\Exception $exception) {
            $this->assertInstanceOf(FlickrGeneralException::class, $exception);
        }

        $this->assertSoftDeleted($contact);
    }
}
