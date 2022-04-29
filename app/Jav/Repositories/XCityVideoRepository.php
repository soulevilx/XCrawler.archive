<?php

namespace App\Jav\Repositories;

use App\Core\Repositories\Traits\HasDefaultRepository;
use App\Jav\Models\XCityVideo;
use Illuminate\Database\Eloquent\Model;

class XCityVideoRepository
{
    use HasDefaultRepository;

    public function __construct(public XCityVideo $model)
    {
    }

    public function create(array $attributes): Model
    {
        return $this->model->withTrashed()->firstOrCreate([
            'url' => $attributes['url'],
        ], $attributes);
    }
}
