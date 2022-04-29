<?php

namespace App\Jav\Tests\Unit\Jobs;

use App\Jav\Models\State;
use App\Jav\Jobs\XCity\InitVideoIndex;
use App\Jav\Tests\JavTestCase;
use App\Jav\Tests\Traits\XCityVideoMocker;

class XCityInitVideoIndexTest extends JavTestCase
{
    use XCityVideoMocker;

    public function setUp(): void
    {
        parent::setUp();


        $this->loadXCityVideoMocker();
    }

    public function testJob()
    {
        InitVideoIndex::dispatch([
            'from_date' => '20010101',
            'to_date' => '20010102'
        ]);

        foreach ([75846, 6594, 6592] as $id) {
            $this->assertDatabaseHas('xcity_videos', [
                'url' => '/avod/detail/?id=' . $id,
                'state_code' => State::STATE_INIT,
            ]);
        }
    }
}
