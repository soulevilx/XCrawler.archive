<?php

namespace App\Core\Tests\Unit\Models;

use App\Core\Models\State;
use Tests\TestCase;

class StateTest extends TestCase
{
    public function testModel()
    {
        $this->assertInstanceOf(State::class, State::factory()->create());
    }
}
