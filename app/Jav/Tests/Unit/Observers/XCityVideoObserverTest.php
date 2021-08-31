<?php

namespace App\Jav\Tests\Unit\Observers;

use App\Jav\Models\XCityVideo;
use App\Jav\Tests\JavTestCase;

class XCityVideoObserverTest extends JavTestCase
{
    public function testOnVideoCompleted()
    {
        $video = XCityVideo::factory()->create();
        $video->completed();

        $this->assertDatabaseHas('movies', [
            'name' => $video->name,
            'cover' => $video->cover,
            'dvd_id' => $video->dvd_id,
        ]);
    }
}
