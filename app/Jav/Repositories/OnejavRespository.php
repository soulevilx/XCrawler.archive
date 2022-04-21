<?php

namespace App\Jav\Repositories;

use App\Core\Repositories\Traits\HasDefaultRepository;
use App\Jav\Models\Onejav;
use ArrayObject;
use Illuminate\Support\Collection;

class OnejavRespository
{
    use HasDefaultRepository;

    public function __construct(public Onejav $model)
    {
    }

    public function create(array $attributes): Onejav
    {
        return $this->model->withTrashed()->updateOrCreate([
            'url' => $attributes['url'],
        ], $attributes);
    }

    public function createFromArrayObject(ArrayObject $object): Onejav
    {
        return $this->create(array_merge([
            'url' => $object->url,
        ], $object->getArrayCopy()));
    }

    public function createItems(Collection $items): void
    {
        if ($items->isEmpty()) {
            return;
        }

        $items->each(function ($item) {
            $this->createFromArrayObject($item);
        });
    }
}
