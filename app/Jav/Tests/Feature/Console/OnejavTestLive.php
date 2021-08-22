<?php

namespace App\Jav\Tests\Feature\Console;

use App\Jav\Tests\JavTestCase;

class OnejavTestLive extends JavTestCase
{
    public function testOnejavDaily()
    {
        $this->artisan('jav:onejav daily');
    }

    public function testOnejavRelease()
    {
        $this->artisan('jav:onejav release');
    }
}
