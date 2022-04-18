<?php

namespace App\Jav\Tests\Unit\Observers;

use App\Core\Models\State;
use App\Jav\Models\Performer;
use App\Jav\Models\XCityIdol;
use App\Jav\Tests\JavTestCase;

class XCityIdolObserverTest extends JavTestCase
{
    public function testOnIdolCompleted()
    {
        $idol = XCityIdol::factory()->create();
        $idol->update([
            'state_code' => State::STATE_COMPLETED,
        ]);

        $this->assertDatabaseHas('performers', [
            'name' => $idol->name,
            'cover' => $idol->cover,
            'city' => $idol->city,
            'height' => $idol->height,
            'breast' => $idol->breast,
            'waist' => $idol->waist,
            'hips' => $idol->hips,
        ]);
    }

    public function testOnIdolCompletedWithExistsPerformer()
    {
        $performer = Performer::factory()->create();
        $idol = XCityIdol::factory()->create([
            'name' => $performer->name,
        ]);

        $idol->update([
            'state_code' => State::STATE_COMPLETED,
        ]);

        $this->assertDatabaseCount('performers', 1);
        $this->assertDatabaseHas('performers', [
            'name' => $idol->name,
        ]);
    }
}
