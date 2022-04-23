<?php

namespace App\Jav\Events\SCute;

use App\Jav\Models\SCute;

class SCuteCompleted
{
    public function __construct(public SCute $model)
    {
    }
}
