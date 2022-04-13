<?php

namespace App\Flickr\Tests\Unit\Listeners;

use App\Core\Models\State;
use App\Flickr\Events\FlickrContactCreated;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Tests\FlickrTestCase;
use Illuminate\Support\Facades\Event;

class FlickrContactEventSubscriberTest extends FlickrTestCase
{
    public function testFlickrContactCreated()
    {
        $contact = FlickrContact::factory()->create();
        Event::dispatch(new FlickrContactCreated($contact));

        $this->assertDatabaseHas('flickr_processes', [
            'step' => FlickrProcess::STEP_PEOPLE_INFO,
            'state_code' => State::STATE_INIT,
        ]);

        $this->assertDatabaseHas('flickr_processes', [
            'step' => FlickrProcess::STEP_PHOTOSETS_LIST,
            'state_code' => State::STATE_INIT,
        ]);

        $this->assertDatabaseHas('flickr_processes', [
            'model_id' => $contact->id,
            'model_type' => FlickrContact::class,
            'step' => FlickrProcess::STEP_PEOPLE_INFO
        ]);

        $this->assertDatabaseHas('flickr_processes', [
            'model_id' => $contact->id,
            'model_type' => FlickrContact::class,
            'step' => FlickrProcess::STEP_PEOPLE_PHOTOS
        ]);

        $this->assertDatabaseHas('flickr_processes', [
            'model_id' => $contact->id,
            'model_type' => FlickrContact::class,
            'step' => FlickrProcess::STEP_PEOPLE_FAVORITE_PHOTOS
        ]);

        $this->assertDatabaseHas('flickr_processes', [
            'model_id' => $contact->id,
            'model_type' => FlickrContact::class,
            'step' => FlickrProcess::STEP_PHOTOSETS_LIST
        ]);
    }
}
