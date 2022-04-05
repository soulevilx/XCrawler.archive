<?php

namespace App\Jav\Repositories;

use App\Core\Repositories\AbstractRepository;
use App\Jav\Models\Onejav;

class OnejavRespository extends AbstractRepository
{
    public function __construct(protected $model)
    {
    }
}
