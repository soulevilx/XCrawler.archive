<?php

namespace App\Flickr\Tests\Unit\Services;

use App\Flickr\Events\FlickrRequestFailed;
use App\Flickr\Exceptions\FlickrGeneralException;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Services\Flickr\Contacts;
use App\Flickr\Services\Flickr\People;
use App\Flickr\Services\FlickrService;
use App\Flickr\Tests\FlickrTestCase;
use Illuminate\Support\Facades\Event;

class FlickrServiceTest extends FlickrTestCase
{
    public function testRequestFailed()
    {
        Event::fake(FlickrRequestFailed::class);
        $this->expectException(FlickrGeneralException::class);

        // User deleted
        $this->service->people()->getInfo('deleted');
        $this->assertDatabaseHas('client_requests', [
            'service' => FlickrService::SERVICE,
            'endpoint' => 'flickr.people.getInfo',
            'code' => People::ERROR_CODE_USER_DELETED
        ], 'mongodb');

        // General cases
        $this->service->people()->getInfo('null');
        $this->assertDatabaseHas('client_requests', [
            'service' => FlickrService::SERVICE,
            'endpoint' => 'flickr.people.getInfo',
            'code' => 999,
        ], 'mongodb');

        // Exception
        $this->service->people()->getInfo('exception');
        $this->assertDatabaseHas('client_requests', [
            'service' => FlickrService::SERVICE,
            'endpoint' => 'flickr.people.getInfo',
            'code' => 9999,
            'message' => 'TokenResponseException'
        ], 'mongodb');
        Event::assertDispatched(FlickrRequestFailed::class);
    }

    public function testContacts()
    {
        $this->assertEquals($this->totalContacts, $this->service->contacts()->getListAll()->count());
        $this->assertEquals(
            Contacts::PER_PAGE,
            $this->service->contacts()->getList(null, null, Contacts::PER_PAGE)['contact']->count()
        );
    }

    public function testContactsFailed()
    {
        Event::fake(FlickrRequestFailed::class);
        $this->expectException(FlickrGeneralException::class);
        $this->service->contacts()->getList(null, null, 9999);
        $this->assertDatabaseHas('client_requests', [
            'service' => FlickrService::SERVICE,
            'endpoint' => 'flickr.people.getInfo',
            'code' => Contacts::ERROR_CODE_INVALID_SORT_PARAMETER,
            'message' => 'The possible values are: name and time.'
        ], 'mongodb');
        Event::assertDispatched(FlickrRequestFailed::class);
    }

    public function testPeopleInfo()
    {
        $this->assertEquals($this->nsid, $this->service->people()->getInfo($this->nsid)['nsid']);
        $this->assertEquals('SoulEvilX', $this->service->people()->getInfo($this->nsid)['username']);
        $this->assertEquals(150, $this->service->people()->getPhotos(
            $this->nsid,
            3,
            null,
            null,
            null,
            null,
            null,
            null,
            150
        )['photo']->count());
        $this->assertEquals(358, $this->service->people()->getPhotosAll(
            $this->nsid,
            3,
            null,
            null,
            null,
            null,
            null,
            null,
            150
        )->count());
    }

    public function testPeopleInfoUserDeleted()
    {
        $this->expectException(FlickrGeneralException::class);
        $people = FlickrContact::factory()->create(['nsid' => 'deleted']);
        $this->service->people()->getInfo('deleted');
        $this->assertDatabaseHas('client_requests', [
            'service' => FlickrService::SERVICE,
            'endpoint' => 'flickr.people.getInfo',
            'code' => 1,
        ], 'mongodb');
        $this->assertSoftDeleted($people);
    }

    public function testPeopleGetPhotosUserDeleted()
    {
        $this->expectException(FlickrGeneralException::class);
        $this->assertEmpty($this->service->people()->getPhotos('deleted'));
    }

    public function testPhotos()
    {
        $sizes = $this->service->photos()->getSizes(50068037298);
        $this->assertEquals(11, $sizes['size']->count());
    }

    public function testPhotoSets()
    {
        $photosets = $this->service->photosets()->getList($this->nsid);

        $this->assertEquals(23, $photosets['total']);
        $this->assertEquals(23, $photosets['photoset']->count());

        $photosets = $this->service->photosets()->getListAll($this->nsid);
        $this->assertEquals(23, $photosets->count());
    }

    public function testPhotoSetsNotFound()
    {
        $this->expectException(FlickrGeneralException::class);
        $this->service->photosets()->getInfo(-1, 'deleted');
    }

    public function testPhotoSetsPhotos()
    {
        $photos = $this->service->photosets()->getAllPhotos('72157688392979533', '94529704@N02', null, null, 50);
        $this->assertEquals(98, $photos->count());
    }

    public function testUrls()
    {
        $result = $this->service->urls()->lookupUser($this->faker->url);
        $this->assertEquals('51838687@N07', $result['id']);
        $this->assertEquals('ANGUS PHOTOGRAPHY', $result['username']);
    }
}
