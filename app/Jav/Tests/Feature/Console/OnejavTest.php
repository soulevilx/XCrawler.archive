<?php

namespace App\Jav\Tests\Feature\Console;

use App\Jav\Jobs\Onejav\DailyFetch;
use App\Jav\Jobs\Onejav\ReleaseFetch;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\OnejavMocker;
use Illuminate\Support\Facades\Queue;

class OnejavTest extends JavTestCase
{
    use OnejavMocker;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadOnejavMock();
        Queue::fake();
    }

    public function testOnejavDaily()
    {
        $this->artisan('jav:onejav daily');
        Queue::assertPushed(DailyFetch::class);
    }

    public function testOnejavRelease()
    {
        $this->artisan('jav:onejav release');
        Queue::assertPushed(ReleaseFetch::class);
    }
}
