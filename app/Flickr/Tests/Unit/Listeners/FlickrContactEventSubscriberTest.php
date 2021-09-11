<?php

namespace App\Flickr\Tests\Unit\Listeners;

use App\Core\Models\State;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrContactProcess;
use App\Flickr\Tests\FlickrTestCase;

class FlickrContactEventSubscriberTest extends FlickrTestCase
{
    public function testFlickrContactCreated()
    {
        FlickrContact::factory()->create();
        $this->assertDatabaseHas('flickr_contact_processes', [
            'step' => FlickrContactProcess::STEP_PEOPLE_INFO,
            'state_code' => State::STATE_INIT,
        ]);

        $this->assertDatabaseHas('flickr_contact_processes', [
            'step' => FlickrContactProcess::STEP_PHOTOSETS_LIST,
            'state_code' => State::STATE_INIT,
        ]);
    }
}
