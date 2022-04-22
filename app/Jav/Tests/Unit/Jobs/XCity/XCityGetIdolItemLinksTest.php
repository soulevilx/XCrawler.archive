<?php

namespace App\Jav\Tests\Unit\Jobs\XCity;

use App\Core\Services\Facades\Application;
use App\Jav\Jobs\XCity\Idol\FetchIdolLinks;
use App\Jav\Models\State;
use App\Jav\Models\XCityIdol;
use App\Jav\Services\XCityIdolService;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityIdolMocker;

class XCityGetIdolItemLinksTest extends JavTestCase
{
    use XCityIdolMocker;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadXCityIdolMocker();
    }

    public function testGetIdolItemLinks()
    {
        $kana = $this->faker->randomElement($this->kanas);
        FetchIdolLinks::dispatch($kana);

        $this->assertEquals(1, Application::getSetting(XCityIdolService::SERVICE_NAME, $kana.'_total_pages'));
        $this->assertEquals(1, Application::getSetting(XCityIdolService::SERVICE_NAME, $kana.'_current_page'));

        $this->assertDatabaseCount('xcity_idols', 30);
        $this->assertEquals(30, XCityIdol::byState(State::STATE_INIT)->count());
    }

    public function testGetIdolItemLinksNotAtEndOfPges()
    {
        $kana = $this->faker->randomElement($this->kanas);
        Application::setSetting(XCityIdolService::SERVICE_NAME, $kana.'_total_pages', 2);
        FetchIdolLinks::dispatch($kana);

        $this->assertEquals(2, Application::getSetting(XCityIdolService::SERVICE_NAME, $kana.'_current_page'));

        $this->assertDatabaseCount('xcity_idols', 30);
        $this->assertEquals(30, XCityIdol::byState(State::STATE_INIT)->count());

        FetchIdolLinks::dispatch($kana);
        $this->assertEquals(1, Application::getSetting(XCityIdolService::SERVICE_NAME, $kana.'_current_page'));
    }

    public function testGetIdolItemLinksWithoutUpdateCurrentPage()
    {
        $kana = $this->faker->randomElement($this->kanas);
        FetchIdolLinks::dispatch($kana, 1, false);

        $this->assertNull(Application::getSetting('xcity', $kana.'_current_page'));

        $this->assertDatabaseCount('xcity_idols', 30);
        $this->assertEquals(30, XCityIdol::byState(State::STATE_INIT)->count());
    }
}
