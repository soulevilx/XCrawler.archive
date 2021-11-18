<?php

namespace App\Jav\Tests\Unit\Services;

use App\Core\Services\ApplicationService;
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
        $this->service = app(XCityIdolService::class);
    }

    public function testRelease()
    {
        $this->service->release();

        $subPages = ApplicationService::getConfig('xcity_idol', 'sub_pages');

        $this->assertEquals([
            "/idol/?kana=あ",
            "/idol/?kana=か",
            "/idol/?kana=さ",
            "/idol/?kana=た",
            "/idol/?kana=な",
            "/idol/?kana=は",
            "/idol/?kana=ま",
            "/idol/?kana=や",
            "/idol/?kana=ら",
            "/idol/?kana=わ",
        ], $subPages);

        foreach ($this->kanas as $kana) {
            $this->assertEquals(112, ApplicationService::getConfig('xcity_idol', $kana . '_total_pages'));
            $this->assertEquals(2, ApplicationService::getConfig('xcity_idol', $kana . '_current_page'));
        }

        $this->assertDatabaseCount('xcity_idols', 30);
    }

    public function testReleaseAtEndOfPages()
    {
        foreach ($this->kanas as $kana) {
            ApplicationService::setConfig('xcity_idol', $kana . '_total_pages', 1);
        }

        $this->service->release();
        foreach ($this->kanas as $kana) {
            $this->assertEquals(1, ApplicationService::getConfig('xcity_idol', $kana . '_total_pages'));
            $this->assertEquals(1, ApplicationService::getConfig('xcity_idol', $kana . '_current_page'));
        }

        $this->assertDatabaseCount('xcity_idols', 30);
    }

    public function testDaily()
    {
        $this->service->daily();

        foreach ($this->kanas as $kana) {
            $this->assertNull(ApplicationService::getConfig('xcity', $kana . '_total_pages'));
            $this->assertNull(ApplicationService::getConfig('xcity', $kana . '_current_page'));
        }

        $this->assertDatabaseCount('xcity_idols', 30);
    }

    public function testGetSubPages()
    {
        $this->assertEquals([
            "/idol/?kana=あ",
            "/idol/?kana=か",
            "/idol/?kana=さ",
            "/idol/?kana=た",
            "/idol/?kana=な",
            "/idol/?kana=は",
            "/idol/?kana=ま",
            "/idol/?kana=や",
            "/idol/?kana=ら",
            "/idol/?kana=わ",
        ], $this->service->getSubPages()->toArray());
    }
}
