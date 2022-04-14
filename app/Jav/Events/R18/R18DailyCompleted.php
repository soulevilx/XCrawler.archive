<?php

namespace App\Jav\Events\R18;

use Illuminate\Support\Collection;

class R18DailyCompleted
{
    public function __construct(public Collection $items)
    {
    }
}
