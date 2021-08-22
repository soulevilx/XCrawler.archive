<?php

namespace App\Jav\Tests\Feature\Console;

use App\Jav\Models\Onejav;
use App\Jav\Tests\JavTestCase;

class OnejavTestLive extends JavTestCase
{
    public function testOnejavDaily()
    {
        $this->artisan('jav:onejav daily');
        $this->assertFalse(Onejav::all()->isEmpty());
    }

    public function testOnejavRelease()
    {
        $this->artisan('jav:onejav release');
        $this->assertFalse(Onejav::all()->isEmpty());
    }
}
