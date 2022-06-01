<?php

namespace App\Jav\Tests\Unit\Jobs\XCity;

use App\Jav\Jobs\XCity\Idol\FetchIdol;
use App\Jav\Models\State;
use App\Jav\Models\XCityIdol;
use App\Jav\Services\XCityIdolService;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityIdolMocker;
use Exception;

class FetchIdolTest extends JavTestCase
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
        FetchIdol::dispatch($model);

        $model->refresh();
        $this->assertEquals(State::STATE_COMPLETED, $model->state_code);
        $this->assertEquals('Yuna Ogura', $model->name);
    }

    public function testItemFetchFailed()
    {
        $this->expectException(Exception::class);
        $service = \Mockery::mock(XCityIdolService::class);
        $service->shouldReceive('item')
            ->andThrow(new Exception);
        app()->instance(XCityIdolService::class, $service);

        $model = XCityIdol::factory()->create([
            'url' => 'detail/13125',
        ]);
        FetchIdol::dispatch($model);

        $this->assertEquals(State::STATE_FAILED, $model->refresh()->state_code);
    }
}
