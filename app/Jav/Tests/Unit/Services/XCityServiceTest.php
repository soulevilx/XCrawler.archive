<?php

namespace App\Jav\Tests\Unit\Services;

use App\Core\Services\ApplicationService;
use App\Jav\Services\XCityService;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityIdolMocker;

class XCityServiceTest extends JavTestCase
{
    use XCityIdolMocker;

    private XCityService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadXCityIdolMocker();
        $this->service = app(XCityService::class);
    }

    public function testRelease()
    {
        $this->service->release();

        foreach ($this->kanas as $kana) {
            $this->assertEquals(112, ApplicationService::getConfig('xcity', $kana.'_total_pages'));
            $this->assertEquals(2, ApplicationService::getConfig('xcity', $kana.'_current_page'));
        }

        $this->assertDatabaseCount('xcity_idols', 30);
    }

    public function testDaily()
    {
        $this->service->daily();

        foreach ($this->kanas as $kana) {
            $this->assertNull(ApplicationService::getConfig('xcity', $kana.'_total_pages'));
            $this->assertNull(ApplicationService::getConfig('xcity', $kana.'_current_page'));
        }

        $this->assertDatabaseCount('xcity_idols', 30);
    }
}
