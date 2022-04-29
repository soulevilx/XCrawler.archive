<?php

namespace App\Jav\Tests\Unit\Jobs\SCute;

use App\Core\Services\Facades\Application;
use App\Jav\Jobs\SCute\ItemFetch;
use App\Jav\Jobs\SCute\ReleaseFetch;
use App\Jav\Models\SCute;
use App\Jav\Models\State;
use App\Jav\Services\SCuteService;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\SCuteMocker;

class ItemFetchTest extends JavTestCase
{
    use SCuteMocker;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadSCuteMocker();
    }

    public function testItem()
    {
        $model = SCute::factory()->create([
            'state_code' => State::STATE_INIT,
            'images' => null,
        ]);
        ItemFetch::dispatch($model);

        $model->refresh();

        $this->assertEquals(State::STATE_COMPLETED, $model->state_code);
        $this->assertIsArray($model->images);
    }
}
