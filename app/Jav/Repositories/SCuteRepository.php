<?php

namespace App\Jav\Repositories;

use App\Core\Repositories\Traits\HasDefaultRepository;
use App\Jav\Models\SCute;

class SCuteRepository
{
    use HasDefaultRepository;

    public function __construct(public SCute $model)
    {
    }

    public function create(array $attributes): SCute
    {
        return $this->model->firstOrCreate([
            'url' => $attributes['url'],
        ], $attributes);
    }
}
