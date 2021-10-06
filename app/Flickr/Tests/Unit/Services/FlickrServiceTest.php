<?php

namespace App\Flickr\Tests\Unit\Services;

use App\Flickr\Exceptions\FlickrRequestFailed;
use App\Flickr\Services\FlickrService;
use App\Flickr\Tests\FlickrTestCase;

class FlickrServiceTest extends FlickrTestCase
{
    public function testRequestFailed()
    {
        $this->expectException(FlickrRequestFailed::class);
        $this->service->request('flickr.contacts.getList', ['fail' => true]);
        $this->assertDatabaseHas('request_failed', [
            'service' => FlickrService::SERVICE,
            'path' => 'flickr.contacts.getList'
        ]);
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
}
