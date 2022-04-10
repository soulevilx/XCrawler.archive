<?php

namespace App\Jav\Tests\Unit\Jobs;

use App\Core\Models\State;
use App\Core\Services\ApplicationService;
use App\Core\Services\Facades\Application;
use App\Jav\Jobs\R18\DailyFetch;
use App\Jav\Jobs\R18\ReleaseFetch;
use App\Jav\Models\R18;
use App\Jav\Services\R18Service;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\R18Mocker;

class R18ReleaseFetchTest extends JavTestCase
{
    use R18Mocker;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadR18Mocker();
    }

    public function testRelease()
    {
        ReleaseFetch::dispatch();

        $this->assertEquals(30, R18::byState(State::STATE_INIT)->count());
        $this->assertEquals(2, Application::getSetting(R18Service::SERVICE_NAME, 'release_current_page'));
    }

    public function testDaily()
    {
        DailyFetch::dispatch();

        $this->assertEquals(30, R18::byState(State::STATE_INIT)->count());
        $this->assertDatabaseCount('r18', 30);
    }
}
