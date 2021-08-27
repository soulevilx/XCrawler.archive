<?php

namespace App\Jav\Tests\Unit\Models;

use App\Core\Models\State;
use App\Jav\Events\XCityIdolCompleted;
use App\Jav\Models\XCityIdol;
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
        $idol->update([
            'state_code' => State::STATE_COMPLETED,
        ]);

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

        $idol->refetch();
        $this->assertEquals('Yuna Ogura', $idol->name);
    }
}
