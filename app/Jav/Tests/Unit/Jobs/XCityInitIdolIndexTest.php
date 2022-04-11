<?php

namespace App\Jav\Tests\Unit\Jobs;

use App\Core\Services\Facades\Application;
use App\Jav\Jobs\XCity\InitIdolIndex;
use App\Jav\Services\XCityIdolService;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityIdolMocker;

class XCityInitIdolIndexTest extends JavTestCase
{
    use XCityIdolMocker;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadXCityIdolMocker();
    }

    public function testInitIdolIndex()
    {
        $kana = $this->faker->randomElement($this->kanas);
        InitIdolIndex::dispatch($kana);

        $this->assertEquals(112, Application::getSetting(XCityIdolService::SERVICE_NAME, $kana.'_total_pages'));
    }

    public function testInitIdolIndexWithTotalPagesExists()
    {
        $kana = $this->faker->randomElement($this->kanas);
        Application::setSetting(XCityIdolService::SERVICE_NAME, $kana.'_total_pages', 10);
        InitIdolIndex::dispatch($kana);

        $this->assertEquals(10, Application::getSetting(XCityIdolService::SERVICE_NAME, $kana.'_total_pages'));
    }
}
