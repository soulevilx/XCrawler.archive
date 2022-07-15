<?php

namespace App\Jav\Tests\Unit\Jobs;

use App\Core\Services\Facades\Application;
use App\Jav\Jobs\Onejav\ReleaseFetch;
use App\Jav\Services\OnejavService;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\OnejavMocker;

class OnejavReleaseFetchTest extends JavTestCase
{
    use OnejavMocker;

    public function testReleaseFetchJob()
    {
        ReleaseFetch::dispatch();

        $this->assertDatabaseCount('onejav', 10);
        $this->assertDatabaseCount('movies', 10);

        $this->assertEquals(2, Application::getSetting(OnejavService::SERVICE_NAME, 'current_page'));
    }
}
