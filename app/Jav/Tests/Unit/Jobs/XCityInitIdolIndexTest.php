<?php

namespace App\Jav\Tests\Unit\Jobs;

use App\Core\Services\ApplicationService;
use App\Jav\Jobs\XCity\InitIdolIndex;
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

        $this->assertEquals(112, ApplicationService::getConfig('xcity_idol', $kana.'_total_pages'));
    }

    public function testInitIdolIndexWithTotalPagesExists()
    {
        $kana = $this->faker->randomElement($this->kanas);
        ApplicationService::setConfig('xcity_idol', $kana.'_total_pages', 10);
        InitIdolIndex::dispatch($kana);

        $this->assertEquals(10, ApplicationService::getConfig('xcity_idol', $kana.'_total_pages'));
    }
}
