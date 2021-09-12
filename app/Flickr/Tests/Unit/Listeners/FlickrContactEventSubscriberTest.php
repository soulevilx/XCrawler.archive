<?php

namespace App\Flickr\Tests\Unit\Listeners;

use App\Core\Models\State;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Tests\FlickrTestCase;

class FlickrContactEventSubscriberTest extends FlickrTestCase
{
    public function testFlickrContactCreated()
    {
        FlickrContact::factory()->create();
        $this->assertDatabaseHas('flickr_contact_processes', [
            'step' => FlickrProcess::STEP_PEOPLE_INFO,
            'state_code' => State::STATE_INIT,
        ]);

        $this->assertDatabaseHas('flickr_contact_processes', [
            'step' => FlickrProcess::STEP_PHOTOSETS_LIST,
            'state_code' => State::STATE_INIT,
        ]);
    }
}
