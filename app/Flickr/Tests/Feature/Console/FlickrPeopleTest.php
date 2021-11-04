<?php

namespace App\Flickr\Tests\Feature\Console;

use App\Core\Models\State;
use App\Flickr\Jobs\FlickrPeopleInfo;
use App\Flickr\Jobs\FlickrPeoplePhotos;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Tests\FlickrTestCase;
use Illuminate\Support\Facades\Queue;

class FlickrPeopleTest extends FlickrTestCase
{
    public function testInfo()
    {
        Queue::fake();
        $contact = FlickrContact::factory()->create();
        $this->artisan('flickr:people info');

        Queue::assertPushed(FlickrPeopleInfo::class, function ($job) use ($contact) {
            return $job->process->model->is($contact);
        });
    }

    public function testInfoWhenNoProcesses()
    {
        Queue::fake();
        $contact = FlickrContact::factory()->create();
        $contact->process()->delete();

        $this->artisan('flickr:people info');

        $this->assertDatabaseHas('flickr_processes', [
            'step' => FlickrProcess::STEP_PEOPLE_INFO,
            'state_code' => State::STATE_INIT,
            'deleted_at' => null,
            'model_id' => $contact->id,
            'model_type' => FlickrContact::class,
        ], 'flickr');
    }

    public function testPhotos()
    {
        Queue::fake();
        $contactProcess = FlickrProcess::factory()->create([
            'step' => FlickrProcess::STEP_PEOPLE_INFO,
            'state_code' => State::STATE_COMPLETED,
        ]);
        $this->artisan('flickr:people photos');

        Queue::assertPushed(FlickrPeoplePhotos::class, function ($job) use ($contactProcess) {
            return $job->process->model->is($contactProcess->model);
        });
    }
}
