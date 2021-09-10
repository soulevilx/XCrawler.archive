<?php

namespace App\Flickr\Tests\Unit\Observers;

use App\Core\Models\State;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrContactProcess;
use App\Flickr\Tests\FlickrTestCase;

class FlickrContactObserverTest extends FlickrTestCase
{
    /**
     * Contact create will create 2 process
     * - STEP_PEOPLE_INFO for getting detail
     * - STEP_PHOTOSETS_LIST for getting photosets
     */
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
