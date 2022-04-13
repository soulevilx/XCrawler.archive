<?php

namespace App\Core\Tests\Unit\Services;

use App\Core\Services\AnalyticsService;
use App\Jav\Models\Onejav;
use App\Jav\Models\R18;
use App\Jav\Models\XCityIdol;
use App\Jav\Models\XCityVideo;
use Carbon\Carbon;
use Tests\TestCase;

class AnalyticsServiceTest extends TestCase
{

    private AnalyticsService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = app(AnalyticsService::class);
    }

    public function testTotal()
    {
        Onejav::factory()->create();
        R18::factory()->create();
        XCityVideo::factory()->create();
        XCityIdol::factory()->create();
        $this->assertEquals(1, $this->service->total()->report()['onejav']['total']);
        $this->assertEquals(1, $this->service->total()->report()['r18']['total']);
        $this->assertEquals(1, $this->service->total()->report()['xcityidol']['total']);
        $this->assertEquals(1, $this->service->total()->report()['xcityidol']['total']);
    }

    public function testToday()
    {
        Onejav::factory()->create([
            'created_at' => Carbon::now()->subDay()
        ]);

        $this->assertEquals(0, $this->service->today()->report()['onejav']['today']);
    }
}
