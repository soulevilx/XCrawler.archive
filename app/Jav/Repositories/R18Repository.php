<?php

namespace App\Jav\Repositories;

use App\Core\Repositories\Traits\HasDefaultRepository;
use App\Jav\Models\R18;

class R18Repository
{
    use HasDefaultRepository;

    public function __construct(public R18 $model)
    {
    }
}
