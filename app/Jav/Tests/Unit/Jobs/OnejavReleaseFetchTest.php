<?php

namespace App\Jav\Tests\Unit\Jobs;

use App\Core\Services\ApplicationService;
use App\Jav\Jobs\Onejav\ReleaseFetch;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\OnejavMocker;

/**
 * @internal
 * @coversNothing
 */
class OnejavReleaseFetchTest extends JavTestCase
{
    use OnejavMocker;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadOnejavMock();
    }

    /**
     * @covers \App\Jav\Jobs\Onejav\ReleaseFetch
     */
    public function testReleaseFetchJob()
    {
        ReleaseFetch::dispatch();

        $this->assertDatabaseCount('onejav', 10);
        $this->assertDatabaseCount('movies', 10);

        $this->assertEquals(1, ApplicationService::getConfig('onejav', 'current_page'));
    }
}
