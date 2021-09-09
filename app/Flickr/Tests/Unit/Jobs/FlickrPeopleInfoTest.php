<?php

namespace App\Flickr\Tests\Unit\Jobs;

use App\Core\Models\State;
use App\Flickr\Jobs\FlickrContacts;
use App\Flickr\Jobs\FlickrPeopleInfo;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrContactProcess;
use App\Flickr\Tests\FlickrTestCase;

class FlickrPeopleInfoTest extends FlickrTestCase
{
    public function testJob()
    {
        $contact = FlickrContact::factory()->create(['nsid' => '94529704@N02']);

        FlickrPeopleInfo::dispatch($contact->contactProcess());
        $contact->refresh();
        $this->assertEquals(State::STATE_PROCESSING, $contact->state_code);
        $this->assertEquals(State::STATE_COMPLETED, $contact->contactProcess()->state_code);
        $this->assertEquals(760, $contact->iconserver);
    }
}
