<?php

namespace App\Flickr\Tests\Unit\Models;

use App\Flickr\Models\FlickrContactProcess;
use App\Flickr\Models\FlickrPhoto;
use App\Flickr\Tests\FlickrTestCase;

class FlickrContact extends FlickrTestCase
{
    public function testGetPhotos()
    {
        $contact = \App\Flickr\Models\FlickrContact::factory()->create();
        $photo = FlickrPhoto::factory()->create([
            'owner' => $contact->nsid,
        ]);

        $this->assertEquals($contact->photos()->first()->owner, $photo->owner);
    }

    public function testProcess()
    {
        $contact = \App\Flickr\Models\FlickrContact::factory()->create();
        $this->assertEquals(2, $contact->process->count());

        $this->assertEquals(FlickrContactProcess::STEP_PEOPLE_INFO, $contact->contactProcess()->step);
        $this->assertTrue($contact->contactProcess()->model->is($contact));
    }
}
