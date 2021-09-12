<?php

namespace App\Flickr\Tests\Unit\Models;

use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Models\FlickrPhoto;
use App\Flickr\Tests\FlickrTestCase;
use App\Flickr\Models\FlickrContact as FlickrContactModel;

class FlickrContactTest extends FlickrTestCase
{
    protected FlickrContactModel $contact;

    public function setUp(): void
    {
        parent::setUp();

        $this->contact = FlickrContactModel::factory()->create();
    }

    public function testProcess()
    {
        $this->assertEquals(3, $this->contact->process->count());
        //$this->assertEquals(FlickrProcess::STEP_PEOPLE_INFO, $this->contact->contactProcess()->step);
        //$this->assertTrue($this->contact->contactProcess()->model->is($this->contact));
    }

    public function testAlbum()
    {
        $album = FlickrAlbum::factory()->create();
        $this->assertTrue($this->contact->refresh()->albums->first()->is($album));
    }

    public function testGetPhotos()
    {
        $photo = FlickrPhoto::factory()->create([
            'owner' => $this->contact->nsid,
        ]);

        $this->assertEquals($this->contact->photos()->first()->owner, $photo->owner);
    }
}
