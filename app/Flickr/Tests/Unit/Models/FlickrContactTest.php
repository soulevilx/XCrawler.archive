<?php

namespace App\Flickr\Tests\Unit\Models;

use App\Flickr\Events\AlbumCreated;
use App\Flickr\Events\FlickrContactCreated;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrContact as FlickrContactModel;
use App\Flickr\Models\FlickrPhoto;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Services\FlickrService;
use App\Flickr\Tests\FlickrTestCase;
use Illuminate\Support\Facades\Event;

class FlickrContactTest extends FlickrTestCase
{
    protected FlickrContactModel $contact;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = app(FlickrService::class);
        $this->contact = FlickrContactModel::factory()->create();
    }

    public function testProcess()
    {
        Event::dispatch(new FlickrContactCreated($this->contact));
        $this->assertEquals(4, $this->contact->processes->count());

        $this->assertDatabaseHas('flickr_processes', [
            'model_id' => $this->contact->id,
            'model_type' => FlickrContact::class,
            'step' => FlickrProcess::STEP_PEOPLE_INFO
        ]);

        $this->assertDatabaseHas('flickr_processes', [
            'model_id' => $this->contact->id,
            'model_type' => FlickrContact::class,
            'step' => FlickrProcess::STEP_PEOPLE_PHOTOS
        ]);

        $this->assertDatabaseHas('flickr_processes', [
            'model_id' => $this->contact->id,
            'model_type' => FlickrContact::class,
            'step' => FlickrProcess::STEP_PEOPLE_FAVORITE_PHOTOS
        ]);

        $this->assertDatabaseHas('flickr_processes', [
            'model_id' => $this->contact->id,
            'model_type' => FlickrContact::class,
            'step' => FlickrProcess::STEP_PHOTOSETS_LIST
        ]);

        $this->contact->processes->first()->model->is($this->contact);
    }

    public function testAlbum()
    {
        $album = FlickrAlbum::factory()->create();
        Event::dispatch(new AlbumCreated($album));
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
