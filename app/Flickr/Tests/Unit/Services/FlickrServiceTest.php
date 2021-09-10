<?php

namespace App\Flickr\Tests\Unit\Services;

use App\Flickr\Tests\FlickrTestCase;

class FlickrServiceTest extends FlickrTestCase
{
    public function testContacts()
    {
        $this->assertEquals(1105, $this->service->contacts()->getListAll()->count());
        $this->assertEquals(200, $this->service->contacts()->getList(null, null, 200)['contact']->count());
    }

    public function testPeopleInfo()
    {
        $this->assertEquals('94529704@N02', $this->service->people()->getInfo('94529704@N02')['nsid']);
    }

    public function testPeoplePhotos()
    {
        $this->assertEquals(1, $this->service->people()->getPhotos('94529704@N02')['photo']->count());
        $this->assertEquals(1, $this->service->people()->getPhotosAll('94529704@N02')->count());
    }
}
