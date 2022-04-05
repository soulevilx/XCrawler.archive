<?php

namespace App\Jav\Repositories;

use App\Core\Repositories\AbstractRepository;
use App\Jav\Models\XCityVideo;

class XCityVideoRepository extends AbstractRepository
{
    public function __construct(protected XCityVideo $model)
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
