<?php

namespace App\Jav\Events;

use App\Jav\Models\XCityIdol;

class XCityIdolCompleted
{
public function __construct(public XCityIdol $model)
{

}

}
