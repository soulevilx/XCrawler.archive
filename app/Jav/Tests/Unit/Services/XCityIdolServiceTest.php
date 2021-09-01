<?php

namespace App\Jav\Tests\Unit\Services;

use App\Core\Services\ApplicationService;
use App\Jav\Services\XCityIdolService;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityIdolMocker;
use Illuminate\Support\Facades\Cache;

class XCityIdolServiceTest extends JavTestCase
{
    use XCityIdolMocker;

    private XCityIdolService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadXCityIdolMocker();
        $this->service = app(XCityIdolService::class);
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

    public function testReleaseAtEndOfPages()
    {
        foreach ($this->kanas as $kana) {
            ApplicationService::setConfig('xcity', $kana.'_total_pages', 1);
        }

        $this->service->release();
        foreach ($this->kanas as $kana) {
            $this->assertEquals(1, ApplicationService::getConfig('xcity', $kana.'_total_pages'));
            $this->assertEquals(1, ApplicationService::getConfig('xcity', $kana.'_current_page'));
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

    public function testGetSubPages()
    {
        Cache::set('xcity_idols_sub_pages', 10);
        $this->assertEquals(10, $this->service->getSubPages());
    }
}
