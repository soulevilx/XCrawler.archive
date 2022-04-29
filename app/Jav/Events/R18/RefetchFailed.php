<?php

namespace App\Jav\Events\R18;

use App\Jav\Models\R18;

class RefetchFailed
{
    public function __construct(public R18 $model)
    {
    }
}
