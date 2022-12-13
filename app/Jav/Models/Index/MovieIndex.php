<?php

namespace App\Jav\Models\Index;

use App\Core\Models\BaseMongo;
use App\Jav\Models\Onejav;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MovieIndex extends BaseMongo
{
    protected $collection = 'movies';

    protected $guarded = [];

    public function onejav(): HasOne
    {
        return new HasOne(
            app(Onejav::class)->newQuery(),
            $this,
            'dvd_id',
            'dvd_id'
        );
    }
}
