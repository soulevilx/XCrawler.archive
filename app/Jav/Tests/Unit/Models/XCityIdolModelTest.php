<?php

namespace App\Jav\Tests\Unit\Models;

use App\Core\Models\State;
use App\Jav\Events\XCityIdolCompleted;
use App\Jav\Models\XCityIdol;
use App\Jav\Tests\JavTestCase;
use Illuminate\Support\Facades\Event;


class XCityIdolModelTest extends JavTestCase
{
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
}
