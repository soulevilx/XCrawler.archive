<?php

namespace App\Flickr\Tests\Unit\Jobs;

use App\Core\Models\State;
use App\Flickr\Jobs\FlickrContacts;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Tests\FlickrTestCase;

class FlickrContactsTest extends FlickrTestCase
{
    public function testJob()
    {
        FlickrContacts::dispatch();

        $this->assertDatabaseCount('flickr_contacts', $this->totalContacts);
        $this->assertEquals($this->totalContacts, FlickrContact::where(['state_code' => State::STATE_INIT])->count());

        // Execute command again will not create duplicate contacts
        $this->assertDatabaseCount('flickr_contacts', $this->totalContacts);
        $this->assertEquals($this->totalContacts, FlickrContact::where(['state_code' => State::STATE_INIT])->count());
    }
}
