<?php

namespace App\Jav\Tests\Feature\Console;

use App\Core\Services\ApplicationService;
use App\Jav\Jobs\XCity\InitVideoIndex;
use App\Jav\Services\XCityVideoService;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityVideoMocker;
use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;

class XCityVideoTest extends JavTestCase
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
        ApplicationService::setConfig('xcity_video', 'from_date', $date->format('Ymd'));
        $this->artisan('jav:xcity-video release');

        Queue::assertPushed(InitVideoIndex::class, function ($job) use ($date) {
            return $job->data['from_date'] === $date->format('Ymd') && $job->data['to_date'] === $date->addDay()->format('Ymd');
        });

        $this->assertEquals($date->format('Ymd'), ApplicationService::getConfig('xcity_video', 'from_date'));
    }
}
