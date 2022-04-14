<?php

namespace App\Jav\Events\Onejav;

use Illuminate\Support\Collection;

class OnejavReleaseCompleted
{
    public function __construct(public Collection $items)
    {
    }
}
