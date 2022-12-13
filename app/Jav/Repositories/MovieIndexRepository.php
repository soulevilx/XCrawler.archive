<?php

namespace App\Jav\Repositories;

use App\Jav\Models\Index\MovieIndex;

class MovieIndexRepository
{
    public function __construct(public MovieIndex $model)
    {
    }

    public function findByDvdId(string $dvdId): MovieIndex
    {
        return $this->model->where('dvd_id', $dvdId)->first();
    }
}
