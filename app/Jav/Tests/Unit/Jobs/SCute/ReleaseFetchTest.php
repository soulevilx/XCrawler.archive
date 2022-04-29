<?php

namespace App\Jav\Tests\Unit\Jobs\SCute;

use App\Core\Services\Facades\Application;
use App\Jav\Jobs\SCute\ReleaseFetch;
use App\Jav\Models\SCute;
use App\Jav\Models\State;
use App\Jav\Services\SCuteService;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\SCuteMocker;

class ReleaseFetchTest extends JavTestCase
{
    use SCuteMocker;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadSCuteMocker();
    }

    public function testRelease()
    {
        ReleaseFetch::dispatch();

        $this->assertEquals(2, Application::getSetting(SCuteService::SERVICE_NAME, 'current_page'));
        $this->assertEquals(31, SCute::byState(State::STATE_INIT)->count());
    }
}
