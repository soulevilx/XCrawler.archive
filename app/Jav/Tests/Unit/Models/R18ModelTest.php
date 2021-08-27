<?php

namespace App\Jav\Tests\Unit\Models;

use App\Core\Models\State;
use App\Jav\Models\R18;
use App\Jav\Services\Movie\Observers\MovieObserver;
use Tests\TestCase;


class R18ModelTest extends TestCase
{
    public function testModel()
    {
        /**
         * @var R18 $r18
         */
        $r18 = R18::factory()->create();

        $this->assertNull($r18->movie);

        /**
         * @covers MovieObserver
         */
        $r18->update([
            'state_code' => State::STATE_COMPLETED,
        ]);

        $r18->refresh();
        $this->assertEquals($r18->dvd_id, $r18->movie->dvd_id);
        $this->assertEquals($r18->genres, $r18->movie->genres->pluck('name')->toArray());
        $this->assertEquals($r18->performers, $r18->movie->performers->pluck('name')->toArray());
        $this->assertFalse($r18->movie->is_downloadable);
    }
}
