<?php

namespace App\Jav\Tests\Unit\Services;

use App\Core\Services\Facades\Application;
use App\Jav\Services\XCityIdolService;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityIdolMocker;

class XCityIdolServiceTest extends JavTestCase
{
    use XCityIdolMocker;

    private XCityIdolService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadXCityIdolMocker();
    }

    public function testRelease()
    {
        $this->service->release();

        $subPages = Application::getSetting(XCityIdolService::SERVICE_NAME, 'sub_pages');

        $this->assertEquals(XCityIdolService::SUBPAGES, $subPages);

        foreach ($this->kanas as $kana) {
            $this->assertEquals(112, Application::getSetting(XCityIdolService::SERVICE_NAME, $kana . '_total_pages'));
            $this->assertEquals(2, Application::getSetting(XCityIdolService::SERVICE_NAME, $kana . '_current_page'));
        }

        $this->assertDatabaseCount('xcity_idols', 30);
    }

    public function testReleaseAtEndOfPages()
    {
        foreach ($this->kanas as $kana) {
            Application::getSetting(XCityIdolService::SERVICE_NAME, $kana . '_total_pages', 1);
        }

        $this->service->release();
        foreach ($this->kanas as $kana) {
            $this->assertEquals(1, Application::getSetting(XCityIdolService::SERVICE_NAME, $kana . '_total_pages'));
            $this->assertEquals(1, Application::getSetting(XCityIdolService::SERVICE_NAME, $kana . '_current_page'));
        }

        $this->assertDatabaseCount('xcity_idols', 30);
    }

    public function testDaily()
    {
        $this->service->daily();

        foreach ($this->kanas as $kana) {
            $this->assertNull(Application::getSetting(XCityIdolService::SERVICE_NAME, $kana . '_total_pages'));
            $this->assertNull(Application::getSetting(XCityIdolService::SERVICE_NAME, $kana . '_current_page'));
        }

        $this->assertDatabaseCount('xcity_idols', 30);
    }

    public function testGetSubPages()
    {

        $this->assertEquals(XCityIdolService::SUBPAGES, $this->service->getSubPages());

        $this->assertEquals(XCityIdolService::SUBPAGES, Application::getArray(XCityIdolService::SERVICE_NAME, 'sub_pages'));
    }
}
