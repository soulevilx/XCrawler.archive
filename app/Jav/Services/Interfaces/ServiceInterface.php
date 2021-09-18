<?php

namespace App\Jav\Services\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface ServiceInterface
{
    public function setAttributes(array $attributes): self;
    public function getAttributes(): array;

    public function create(): Model;
    public function item(Model $model): Model;

    public function release();
    public function daily();
}
