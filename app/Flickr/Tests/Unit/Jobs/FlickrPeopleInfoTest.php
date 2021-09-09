<?php

namespace App\Flickr\Tests\Unit\Jobs;

use App\Core\Models\State;
use App\Flickr\Jobs\FlickrContacts;
use App\Flickr\Jobs\FlickrPeopleInfo;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Tests\FlickrTestCase;

class FlickrPeopleInfoTest extends FlickrTestCase
{
    public function testJob()
    {
        $contact = FlickrContact::factory()->create(['nsid' => '94529704@N02']);

        FlickrPeopleInfo::dispatch($contact);
        $contact->refresh();
        $this->assertEquals(State::STATE_COMPLETED, $contact->state_code);
    }
}
