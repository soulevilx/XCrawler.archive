<?php

namespace App\Flickr\Tests\Unit\Jobs;

use App\Core\Models\State;
use App\Flickr\Jobs\FlickrContacts;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrContactProcess;
use App\Flickr\Tests\FlickrTestCase;

class FlickrContactsTest extends FlickrTestCase
{
    public function testJob()
    {
        FlickrContacts::dispatch();
        $this->assertDatabaseCount('flickr_contacts', 1105);
        $this->assertEquals(1105, FlickrContact::where(['state_code' => State::STATE_INIT])->count());
        $this->assertEquals(1105, FlickrContactProcess::where([
            'step' => FlickrContactProcess::STEP_PEOPLE_INFO,
            'state_code' => State::STATE_INIT,
        ])->count());
    }
}
