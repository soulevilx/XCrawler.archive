<?php

namespace App\Jav\Tests\Unit\Models;

use App\Core\Models\State;
use App\Jav\Events\XCityVideoCompleted;
use App\Jav\Models\Performer;
use App\Jav\Models\XCityIdol;
use App\Jav\Models\XCityVideo;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityVideoMocker;
use Illuminate\Support\Facades\Event;

class XCityVideoModelTest extends JavTestCase
{
    use XCityVideoMocker;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadXCityVideoMocker();
    }

    public function testModel()
    {
        Event::fake([XCityVideoCompleted::class]);
        $video = XCityVideo::factory()->create();
        $video->completed();

        Event::assertDispatched(XCityVideoCompleted::class, function ($event) use ($video) {
            return $event->model->is($video);
        });
    }

    public function testRefetch()
    {
        $video = XCityVideo::factory()->create([
            'url' => '/avod/detail/?id=147028',
        ]);

        $video->refetch();
        $this->assertEquals('NACR292', $video->item_number);
        $this->assertEquals([
            'Kaho Imai',
        ], $video->actresses);
    }
}
