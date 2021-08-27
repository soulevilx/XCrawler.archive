<?php

namespace App\Jav\Tests\Unit\Jobs;

use App\Core\Services\ApplicationService;
use App\Jav\Jobs\Onejav\ReleaseFetch;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\OnejavMocker;

class OnejavReleaseFetchTest extends JavTestCase
{
    use OnejavMocker;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadOnejavMock();
    }

    public function testReleaseFetchJob()
    {
        ReleaseFetch::dispatch();

        $this->assertDatabaseCount('onejav', 10);
        $this->assertDatabaseCount('movies', 10);

        $this->assertEquals(1, ApplicationService::getConfig('onejav', 'current_page'));
    }
}
