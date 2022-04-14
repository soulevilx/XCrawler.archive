<?php

namespace App\Jav\Repositories;

use App\Core\Repositories\Traits\HasDefaultRepository;
use App\Jav\Models\XCityIdol;

class XCityIdolRepository
{
    use HasDefaultRepository;

    public function __construct(public XCityIdol $model)
    {
    }
}
