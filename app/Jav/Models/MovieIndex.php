<?php

namespace App\Jav\Models;

use App\Core\Models\BaseMongo;

class MovieIndex extends BaseMongo
{
    protected $collection = 'movies';

    protected $guarded = [];
}
