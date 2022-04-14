<?php

namespace App\Flickr\Tests\Unit\Jobs;

use App\Core\Models\State;
use App\Flickr\Events\FlickrContactCreated;
use App\Flickr\Jobs\FlickrFavorites;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Tests\FlickrTestCase;
use Illuminate\Support\Facades\Event;

class FlickrFavoritesJobTest extends FlickrTestCase
{
    public function testJob()
    {
        $contact = FlickrContact::factory()->create([
            'nsid' => '50419993@N05',
            'state_code' => State::STATE_INIT,
        ]);

        Event::dispatch(new FlickrContactCreated($contact));

        $process = $contact->processes()->where('step', FlickrProcess::STEP_PEOPLE_FAVORITE_PHOTOS)->first();
        FlickrFavorites::dispatch($process);

        $this->assertEquals(State::STATE_COMPLETED, $process->refresh()->state_code);
    }

    public function testWithDeletecContact()
    {
        $deletedContact = FlickrContact::factory()->create([
            'nsid' => '123789481@N03',
            'state_code' => State::STATE_INIT,
        ]);

        $contact = FlickrContact::factory()->create([
            'nsid' => '50419993@N05',
            'state_code' => State::STATE_INIT,
        ]);

        Event::dispatch(new FlickrContactCreated($deletedContact));

        $deletedContact->delete();

        Event::dispatch(new FlickrContactCreated($contact));

        $process = $contact->processes()->where('step', FlickrProcess::STEP_PEOPLE_FAVORITE_PHOTOS)->first();
        FlickrFavorites::dispatch($process);

        // This contact already deleted we wont sync photos
        $this->assertDatabaseMissing('flickr_photos', [
            'owner' => '123789481@N03',
        ]);
    }
}
