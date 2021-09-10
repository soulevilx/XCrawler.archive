<?php

namespace App\Flickr\Tests\Unit\Services;

use App\Flickr\Tests\FlickrTestCase;

class FlickrServiceTest extends FlickrTestCase
{
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
    }
}
