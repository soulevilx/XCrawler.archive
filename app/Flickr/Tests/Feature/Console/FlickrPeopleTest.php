<?php

namespace App\Flickr\Tests\Feature\Console;

use App\Core\Models\State;
use App\Flickr\Events\FlickrContactCreated;
use App\Flickr\Jobs\FlickrFavorites;
use App\Flickr\Jobs\FlickrPeopleInfo;
use App\Flickr\Jobs\FlickrPeoplePhotos;
use App\Flickr\Jobs\FlickrPhotoSets;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Tests\FlickrTestCase;
use Illuminate\Support\Facades\Event;
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
        Event::dispatch(new FlickrContactCreated($contact));

        $this->artisan('flickr:people info');

        Queue::assertPushed(FlickrPeopleInfo::class, function ($job) use ($contact) {
            return $job->process->model->is($contact);
        });
    }

    public function testInfoWhenNoProcesses()
    {
        $contact = FlickrContact::factory()->create();
        Event::dispatch(new FlickrContactCreated($contact));
        $contact->processes()->delete();

        $this->artisan('flickr:people info');

        Queue::assertNotPushed(FlickrPeopleInfo::class);
        Queue::assertNotPushed(FlickrPeoplePhotos::class);
        Queue::assertNotPushed(FlickrPhotoSets::class);
        Queue::assertNotPushed(FlickrFavorites::class);
    }

    public function testPhotos()
    {
        $contact = FlickrContact::factory()->create();
        Event::dispatch(new FlickrContactCreated($contact));
        $process = $contact->processes()->where([
            'step' => FlickrProcess::STEP_PEOPLE_PHOTOS,
        ])->first();

        $this->artisan('flickr:people photos');

        Queue::assertPushed(FlickrPeoplePhotos::class, function ($job) use ($process) {
            return $job->process->model->is($process->model);
        });
    }

    public function testFavorites()
    {
        $this->artisan('flickr:people favorites');
    }
}
