<?php

namespace App\Jav\Tests\Unit\Jobs;

use App\Core\Models\State;
use App\Core\Services\ApplicationService;
use App\Jav\Jobs\XCity\GetIdolItemLinks;
use App\Jav\Models\XCityIdol;
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
        GetIdolItemLinks::dispatch($kana);

        $this->assertNull(ApplicationService::getConfig('xcity', $kana.'_total_pages'));
        $this->assertEquals(1, ApplicationService::getConfig('xcity', $kana.'_current_page'));

        $this->assertDatabaseCount('xcity_idols', 30);
        $this->assertEquals(30, XCityIdol::byState(State::STATE_INIT)->count());
    }

    public function testGetIdolItemLinksNotAtEndOfPges()
    {
        $kana = $this->faker->randomElement($this->kanas);
        ApplicationService::setConfig('xcity', $kana.'_total_pages', 2);
        GetIdolItemLinks::dispatch($kana);

        $this->assertEquals(2, ApplicationService::getConfig('xcity', $kana.'_current_page'));

        $this->assertDatabaseCount('xcity_idols', 30);
        $this->assertEquals(30, XCityIdol::byState(State::STATE_INIT)->count());

        GetIdolItemLinks::dispatch($kana);
        $this->assertEquals(1, ApplicationService::getConfig('xcity', $kana.'_current_page'));
    }

    public function testGetIdolItemLinksWithoutUpdateCurrentPage()
    {
        $kana = $this->faker->randomElement($this->kanas);
        GetIdolItemLinks::dispatch($kana, 1, false);

        $this->assertNull(ApplicationService::getConfig('xcity', $kana.'_current_page'));

        $this->assertDatabaseCount('xcity_idols', 30);
        $this->assertEquals(30, XCityIdol::byState(State::STATE_INIT)->count());
    }
}
