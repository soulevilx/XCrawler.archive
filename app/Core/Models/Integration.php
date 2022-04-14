<?php

namespace App\Core\Models;

use Illuminate\Database\Eloquent\Builder;

class Integration extends BaseMongo
{
    protected $collection = 'integrations';

    protected $guarded = [];

    public function scopeByService(Builder $builder, string $service)
    {
        return $builder->where(['service' => $service]);
    }
}
