<?php

namespace App\Jav\Tests\Feature\Console;

use App\Jav\Jobs\XCity\GetIdolItemLinks;
use App\Jav\Jobs\XCity\InitIdolIndex;
use App\Jav\Jobs\XCity\ItemFetch;
use App\Jav\Models\XCityIdol;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityIdolMocker;
use Illuminate\Support\Facades\Queue;

/**
 * @internal
 * @coversNothing
 */
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

    public function testItem()
    {
        Queue::fake();
        $model = XCityIdol::factory()->create([
            'url' => 'detail/13125',
        ]);

        $this->artisan('jav:xcity-idol item');

        Queue::assertPushed(function (ItemFetch $job) use ($model) {
            return 'crawling' === $job->queue
                && $model->is($job->model)
                && $job->model->isProcessingState();
        });
    }
}
