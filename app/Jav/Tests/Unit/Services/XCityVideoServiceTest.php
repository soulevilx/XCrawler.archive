<?php

namespace App\Jav\Tests\Unit\Services;

use App\Jav\Models\State;
use App\Core\Services\Facades\Application;
use App\Jav\Jobs\XCity\InitVideoIndex;
use App\Jav\Models\XCityVideo;
use App\Jav\Services\XCityVideoService;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityVideoMocker;
use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;

class XCityVideoServiceTest extends JavTestCase
{
    use XCityVideoMocker;

    private XCityVideoService $service;

    public function setUp(): void
    {
        parent::setUp();

        Queue::fake();

        $this->loadXCityVideoMocker();
    }

    public function testRelease()
    {
        $date = Carbon::createFromFormat('Y-m-d', $this->faker->date);
        Application::setSetting(XCityVideoService::SERVICE_NAME, 'from_date', $date->format('Ymd'));
        $this->service->release();

        Queue::assertPushed(InitVideoIndex::class, function ($job) use ($date) {
            return $job->data['from_date'] === $date->format('Ymd') && $job->data['to_date'] === $date->addDay()->format('Ymd');
        });

        $this->assertEquals($date->format('Ymd'), Application::getSetting(XCityVideoService::SERVICE_NAME, 'from_date'));
    }

    public function testDaily()
    {
        $this->service->daily();
        $date = Carbon::now();
        Queue::assertPushed(InitVideoIndex::class, function ($job) use ($date) {
            return $job->data['from_date'] === $date->format('Ymd') && $job->data['to_date'] === $date->addDay()->format('Ymd');
        });
    }

    public function testItem()
    {
        $video = XCityVideo::factory()->create([
            'url' => '/avod/detail/?id=150786',
        ]);
        $this->service->item($video);
        $video->refresh();

        $this->assertEquals('HBAD530', $video->item_number);
        $this->assertEquals([
            'Kaho Imai',
        ], $video->actresses);
        $this->assertEquals('HIBINO', $video->label);
    }

    public function testCreate()
    {
        $url = $this->faker->url;
        $this->service->create([
            'url' => $url,
        ]);

        $this->assertDatabaseHas('xcity_videos', [
            'url' => $url,
            'state_code' => State::STATE_INIT,
        ]);
    }
}
