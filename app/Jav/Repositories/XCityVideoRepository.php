<?php

namespace App\Jav\Repositories;

use App\Core\Repositories\AbstractRepository;
use App\Jav\Models\XCityVideo;

class XCityVideoRepository extends AbstractRepository
{
    public function __construct(protected $model)
    {
    }
}
