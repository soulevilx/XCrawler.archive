<?php

namespace App\Jav\Repositories;

use App\Core\Repositories\Traits\HasDefaultRepository;
use App\Jav\Models\R18;
use Illuminate\Database\Eloquent\Model;

class R18Repository
{
    use HasDefaultRepository;

    public function __construct(public R18 $model)
    {
    }

    public function create(array $attributes): Model
    {
        return $this->model->withTrashed()->firstOrCreate([
            'url' => $attributes['url'],
            'content_id' => $attributes['content_id'],
        ], $attributes);
    }
}
