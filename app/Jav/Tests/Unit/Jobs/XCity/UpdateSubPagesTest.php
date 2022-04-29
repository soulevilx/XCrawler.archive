<?php

namespace App\Jav\Tests\Unit\Jobs\XCity;

use App\Core\Services\Facades\Application;
use App\Jav\Jobs\XCity\Idol\UpdateSubPages;
use App\Jav\Services\XCityIdolService;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityIdolMocker;

class UpdateSubPagesTest extends JavTestCase
{
    use XCityIdolMocker;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadXCityIdolMocker();
    }

    public function testUpdatePagesCount()
    {
        UpdateSubPages::dispatch();

        $subPages = Application::getSetting(XCityIdolService::SERVICE_NAME, 'sub_pages');
        $this->assertIsArray(
            $subPages
        );

        $this->assertEquals(10, count($subPages));
    }
}
