<?php

namespace App\Jav\Tests\Unit\Models;

use App\Jav\Models\State;
use App\Jav\Events\XCity\XCityIdolCompleted;
use App\Jav\Models\Performer;
use App\Jav\Models\XCityIdol;
use App\Jav\Services\XCityIdolService;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityIdolMocker;
use Illuminate\Support\Facades\Event;

class XCityIdolModelTest extends JavTestCase
{
    use XCityIdolMocker;

    public function testModel()
    {
        Event::fake([XCityIdolCompleted::class]);
        $idol = XCityIdol::factory()->create();
        $idol->completed();

        Event::assertDispatched(XCityIdolCompleted::class, function ($event) use ($idol) {
            return $event->model->is($idol);
        });
    }

    public function testRefetch()
    {
        $this->loadXCityIdolMocker();
        $idol = XCityIdol::factory()->create([
            'url' => 'detail/13125/',
        ]);

        $idol = app(XCityIdolService::class)->refetch($idol);
        $this->assertEquals('Yuna Ogura', $idol->name);
    }

    public function testGetPerformer()
    {
        $idol = XCityIdol::factory()->create();
        $idol->update([
            'state_code' => State::STATE_COMPLETED,
        ]);

        $this->assertInstanceOf(Performer::class, $idol->performer);
    }
}
