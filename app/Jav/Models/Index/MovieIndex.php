<?php

namespace App\Jav\Models\Index;

use App\Core\Models\BaseMongo;

class MovieIndex extends BaseMongo
{
    protected $collection = 'movies';

    protected $guarded = [];
}
