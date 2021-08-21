<?php

namespace App\Jav\Tests\Unit\Models;

use App\Jav\Models\Movie;
use App\Jav\Models\Onejav;
use Tests\TestCase;

class OnejavModelTest extends TestCase
{
    public function testModel()
    {
        $onejav = Onejav::factory()->create();

        $this->assertInstanceOf(Movie::class, $onejav->movie);
        $this->assertEquals($onejav->dvd_id, $onejav->movie->dvd_id);
        $this->assertEquals($onejav->genres, $onejav->movie->genres->pluck('name')->toArray());
        $this->assertEquals($onejav->performers, $onejav->movie->performers->pluck('name')->toArray());
        $this->assertTrue($onejav->movie->is_downloadable);

    }
}
