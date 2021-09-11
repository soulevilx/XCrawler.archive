<?php

namespace App\Flickr\Tests\Feature\Console;

use App\Core\Models\State;
use App\Flickr\Jobs\FlickrPeopleInfo;
use App\Flickr\Jobs\FlickrPeoplePhotos;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrContactProcess;
use App\Flickr\Tests\FlickrTestCase;
use Illuminate\Support\Facades\Queue;

class FlickrPeopleTest extends FlickrTestCase
{
    public function testInfo()
    {
        Queue::fake();
        $contact = FlickrContact::factory()->create();
        $this->artisan('flickr:process-people info');

        Queue::assertPushed(FlickrPeopleInfo::class, function ($job) use ($contact) {
            return $job->contactProcess->model->is($contact);
        });
    }

    public function testInfoWhenNoProcesses()
    {
        Queue::fake();
        $contact = FlickrContact::factory()->create();
        $contact->process()->delete();

        $this->artisan('flickr:process-people info');

        $this->assertDatabaseHas('flickr_contact_processes', [
            'step' => FlickrContactProcess::STEP_PEOPLE_INFO,
            'state_code' => State::STATE_INIT,
            'deleted_at' => null,
            'model_id' => $contact->id,
            'model_type' => FlickrContact::class,
        ]);
    }

    public function testPhotos()
    {
        Queue::fake();
        $contactProcess = FlickrContactProcess::factory()->create([
            'step' => FlickrContactProcess::STEP_PEOPLE_INFO,
            'state_code' => State::STATE_COMPLETED,
        ]);
        $this->artisan('flickr:process-people photos');

        Queue::assertPushed(FlickrPeoplePhotos::class, function ($job) use ($contactProcess) {
            return $job->contactProcess->model->is($contactProcess->model);
        });
    }
}
