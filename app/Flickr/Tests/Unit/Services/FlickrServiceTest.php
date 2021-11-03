<?php

namespace App\Flickr\Tests\Unit\Services;

use App\Flickr\Exceptions\FlickrGeneralException;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Services\FlickrService;
use App\Flickr\Tests\FlickrTestCase;

class FlickrServiceTest extends FlickrTestCase
{
    public function testRequestFailed()
    {
        $this->expectException(FlickrGeneralException::class);
        $this->service->people()->getInfo('deleted');
        $this->assertDatabaseHas('client_requests', [
            'service' => FlickrService::SERVICE,
            'endpoint' => 'flickr.people.getInfo',
        ], 'mongodb');
    }

    public function testRequestFailedNull()
    {
        try {
            $this->service->people()->getInfo('null');
        } catch (\Exception $exception) {
            $this->assertDatabaseHas('client_requests', [
                'service' => FlickrService::SERVICE,
                'endpoint' => 'flickr.people.getInfo',
            ], 'mongodb');
        }
    }

    public function testContacts()
    {
        $this->assertEquals($this->totalContacts, $this->service->contacts()->getListAll()->count());
        $this->assertEquals(1000, $this->service->contacts()->getList(null, null, 1000)['contact']->count());
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
        $people = FlickrContact::factory()->create([
            'nsid' => 'deleted',
        ]);
        $this->service->people()->getInfo('deleted');
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
}
