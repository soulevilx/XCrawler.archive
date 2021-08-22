<?php

namespace App\Jav\Events;

use App\Jav\Models\Movie;

class MovieCreated
{
    public function __construct(public Movie $movie)
    {
    }
}
