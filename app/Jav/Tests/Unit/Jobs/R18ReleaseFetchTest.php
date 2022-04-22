<?php

namespace App\Jav\Tests\Unit\Jobs;

use App\Jav\Jobs\R18\ItemFetch;
use App\Jav\Models\State;
use App\Core\Services\Facades\Application;
use App\Jav\Jobs\R18\DailyFetch;
use App\Jav\Jobs\R18\ReleaseFetch;
use App\Jav\Models\R18;
use App\Jav\Services\R18Service;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\R18Mocker;
use Exception;

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
        ReleaseFetch::dispatch(R18::MOVIE_LIST_URL, 'release');

        $this->assertEquals(30, R18::byState(State::STATE_INIT)->count());
        $this->assertEquals(2, Application::getSetting(R18Service::SERVICE_NAME, 'release_current_page'));
    }

    public function testDaily()
    {
        DailyFetch::dispatch(R18::MOVIE_LIST_URL);

        $this->assertEquals(30, R18::byState(State::STATE_INIT)->count());
        $this->assertDatabaseCount('r18', 30);
    }

    public function testReleaseFailed()
    {
        $this->expectException(Exception::class);
        $mocker = \Mockery::mock(R18Service::class);
        $mocker->shouldReceive('item')
            ->andThrow(new Exception);

        app()->instance(R18Service::class, $mocker);

        $r18 = R18::factory()->create();
        ItemFetch::dispatch($r18);

        $this->assertEquals(State::STATE_FAILED, $r18->refresh()->state_code);
    }
}
