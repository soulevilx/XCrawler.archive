<?php

namespace App\Jav\Repositories;

use App\Core\Repositories\Traits\HasDefaultRepository;
use App\Jav\Models\XCityIdol;
use Illuminate\Database\Eloquent\Model;

class XCityIdolRepository
{
    use HasDefaultRepository;

    public function __construct(public XCityIdol $model)
    {
    }

    public function create(array $attributes): Model
    {
        return $this->model->withTrashed()->firstOrCreate([
            'url' => $attributes['url'],
        ], $attributes);
    }
}
