<?php

namespace App\Flickr\Tests\Unit\Services;

use App\Flickr\Tests\FlickrTestCase;

class FlickrServiceTest extends FlickrTestCase
{
    public function testGetContacts()
    {
        $contacts = $this->service->contacts()->getAll();
        $this->assertEquals(1105, $contacts->count());
    }
}
