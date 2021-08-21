<?php

namespace App\Jav\Tests\Unit\Jobs;

use App\Core\Services\ApplicationService;
use App\Jav\Jobs\Onejav\DailyFetch;
use App\Jav\Jobs\Onejav\ReleaseFetch;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\HasOnejav;

class OnejavDailyFetchTest extends JavTestCase
{
    use HasOnejav;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadOnejavMock();
    }

    public function testDailyFetchJob()
    {
        DailyFetch::dispatch();

        $this->assertDatabaseCount('onejav', 42);
        $this->assertDatabaseCount('movies', 42);
    }

    public function testReleaseFetchJob()
    {
        ReleaseFetch::dispatch();

        $this->assertDatabaseCount('onejav', 10);
        $this->assertDatabaseCount('movies', 10);

        $this->assertEquals(1, ApplicationService::getConfig('onejav', 'current_page'));
    }
}
