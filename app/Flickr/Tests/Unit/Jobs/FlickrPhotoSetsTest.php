<?php

namespace App\Flickr\Tests\Unit\Jobs;

use App\Flickr\Events\FlickrContactCreated;
use App\Flickr\Jobs\FlickrPhotoSets;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Tests\FlickrTestCase;
use Illuminate\Support\Facades\Event;

class FlickrPhotoSetsTest extends FlickrTestCase
{
    public function testJob()
    {
        $contact = FlickrContact::factory()->create();
        Event::dispatch(new FlickrContactCreated($contact));

        $process = $contact->processes()->where('step', FlickrProcess::STEP_PHOTOSETS_LIST)->first();

        FlickrPhotoSets::dispatch($process);

        $this->assertDatabaseCount('flickr_albums', 23);

        // Execute again will not create duplicate
        FlickrPhotoSets::dispatch($process);
        $this->assertDatabaseCount('flickr_albums', 23);
        $this->assertEquals(23, FlickrAlbum::where(['owner' => $contact->nsid])->count());
    }
}
