<?php

namespace App\Flickr\Tests\Unit\Jobs;

use App\Flickr\Jobs\FlickrPhotoSets;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrContactProcess;
use App\Flickr\Tests\FlickrTestCase;

class FlickrPhotoSetsTest extends FlickrTestCase
{
    public function testJob()
    {
        $contact = FlickrContact::factory()->create();
        $process = $contact->process()->where('step', FlickrContactProcess::STEP_PHOTOSETS_LIST)->first();

        FlickrPhotoSets::dispatch($process);

        $this->assertDatabaseCount('flickr_albums', 23);
        $this->assertEquals(23, FlickrAlbum::where(['owner' => $contact->nsid])->count());
    }
}
