<?php

namespace App\Jav\Tests\Feature\Console;

use App\Core\Models\State;
use App\Jav\Models\R18;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\R18Mocker;

class R18TestLive extends JavTestCase
{
    use R18Mocker;

    public function testR18()
    {
        $this->artisan('jav:r18 release');
        $this->assertFalse(R18::all()->isEmpty());
        $this->artisan('jav:r18 item');
        $this->assertDatabaseHas('r18', [
            'state_code' => State::STATE_COMPLETED,
        ]);
    }
}
