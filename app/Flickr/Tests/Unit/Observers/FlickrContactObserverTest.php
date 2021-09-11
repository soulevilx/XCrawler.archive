<?php

namespace App\Flickr\Tests\Unit\Observers;

use App\Flickr\Events\FlickrContactCreated;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Tests\FlickrTestCase;
use Illuminate\Support\Facades\Event;

class FlickrContactObserverTest extends FlickrTestCase
{
    /**
     * Contact create will create 2 process
     * - STEP_PEOPLE_INFO for getting detail
     * - STEP_PHOTOSETS_LIST for getting photosets
     */
    public function testFlickrContactCreated()
    {
        Event::fake([FlickrContactCreated::class]);
        $contact = FlickrContact::factory()->create();

        Event::assertDispatched(FlickrContactCreated::class, function ($event) use ($contact) {
            return $event->contact->is($contact);
        });
    }
}
