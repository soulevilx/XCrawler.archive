<?php

namespace App\Jav\Tests\Unit\Jobs;

use App\Jav\Jobs\Onejav\DailyFetch;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\OnejavMocker;

class OnejavDailyFetchTest extends JavTestCase
{
    use OnejavMocker;

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
}
