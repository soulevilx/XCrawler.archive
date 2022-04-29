<?php

namespace App\Jav\Events\Onejav;

use Illuminate\Support\Collection;

class OnejavDailyCompleted
{
    public function __construct(public Collection $items)
    {
    }
}
