<?php

namespace App\Jav\Repositories;

use App\Core\Repositories\Traits\HasDefaultRepository;
use App\Jav\Models\XCityVideo;

class XCityVideoRepository
{
    use HasDefaultRepository;

    public function __construct(public XCityVideo $model)
    {
    }
}
