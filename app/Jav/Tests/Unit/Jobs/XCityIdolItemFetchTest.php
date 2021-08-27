<?php

namespace App\Jav\Tests\Unit\Jobs;

use App\Core\Models\State;
use App\Jav\Jobs\XCity\ItemFetch;
use App\Jav\Models\XCityIdol;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityIdolMocker;

class XCityIdolItemFetchTest extends JavTestCase
{
    use XCityIdolMocker;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadXCityIdolMocker();
    }

    public function testItemFetch()
    {
        $model = XCityIdol::factory()->create([
            'url' => 'detail/13125',
        ]);
        ItemFetch::dispatch($model);

        $model->refresh();
        $this->assertEquals(State::STATE_COMPLETED, $model->state_code);
        $this->assertEquals('Yuna Ogura', $model->name);
    }
}
