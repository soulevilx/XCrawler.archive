<?php

namespace App\Jav\Tests\Unit\Jobs\XCity;

use App\Core\Services\Facades\Application;
use App\Jav\Jobs\XCity\Idol\UpdatePagesCount;
use App\Jav\Services\XCityIdolService;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityIdolMocker;

class UpdatePagesCountTest extends JavTestCase
{
    use XCityIdolMocker;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadXCityIdolMocker();
    }

    public function testUpdatePagesCount()
    {
        $kana = $this->faker->randomElement($this->kanas);
        UpdatePagesCount::dispatch($kana);

        $this->assertEquals(112, Application::getSetting(XCityIdolService::SERVICE_NAME, $kana.'_total_pages'));
    }
}
