<?php

namespace App\Jav\Repositories;

use App\Core\Repositories\AbstractRepository;
use App\Jav\Models\XCityIdol;

class XCityIdolRepository extends AbstractRepository
{
    public function __construct(protected XCityIdol $model)
    {
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }
}
