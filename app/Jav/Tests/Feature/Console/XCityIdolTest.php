<?php

namespace App\Jav\Tests\Feature\Console;

use App\Jav\Jobs\XCity\GetIdolItemLinks;
use App\Jav\Jobs\XCity\InitIdolIndex;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityIdolMocker;
use Illuminate\Support\Facades\Queue;

class XCityIdolTest extends JavTestCase
{
    use XCityIdolMocker;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadXCityIdolMocker();
    }

    public function testDaily()
    {
        Queue::fake();
        $this->artisan('jav:xcity-idol daily');

        Queue::assertPushed(GetIdolItemLinks::class);
    }

    public function testRelease()
    {
        Queue::fake();
        $this->artisan('jav:xcity-idol release');

        Queue::assertPushedWithChain(InitIdolIndex::class, [
            GetIdolItemLinks::class,
        ]);
    }
}
