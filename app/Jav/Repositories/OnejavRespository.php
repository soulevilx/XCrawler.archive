<?php

namespace App\Jav\Repositories;

use App\Core\Repositories\Traits\HasDefaultRepository;
use App\Jav\Models\Onejav;

class OnejavRespository
{
    use HasDefaultRepository;

    public function __construct(public Onejav $model)
    {
    }
}
