<?php

namespace App\Jav\Events;

use App\Jav\Models\XCityVideo;

class XCityVideoCompleted
{
    public function __construct(public XCityVideo $model)
    {
    }
}
