<?php

namespace App\Jav\Repositories;

use App\Core\Repositories\AbstractRepository;
use App\Jav\Models\XCityIdol;

class XCityIdolRepository extends AbstractRepository
{
    public function __construct(protected $model)
    {
    }
}
