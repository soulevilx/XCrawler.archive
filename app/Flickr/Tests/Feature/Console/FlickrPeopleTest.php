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
    public function setUp(): void
    {
        parent::setUp();

        Queue::fake();
    }

    public function testInfo()
    {
        $contact = FlickrContact::factory()->create();
        $this->artisan('flickr:people info');

        Queue::assertPushed(FlickrPeopleInfo::class, function ($job) use ($contact) {
            return $job->process->model->is($contact);
        });
    }

    public function testInfoWhenNoProcesses()
    {
        $contact = FlickrContact::factory()->create();
        $contact->processes()->delete();

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
        $contact = FlickrContact::factory()->create();
        $process = $contact->processes()->where([
            'step' => FlickrProcess::STEP_PEOPLE_PHOTOS,
        ])->first();

        $this->artisan('flickr:people photos');

        Queue::assertPushed(FlickrPeoplePhotos::class, function ($job) use ($process) {
            return $job->process->model->is($process->model);
        });
    }
}
