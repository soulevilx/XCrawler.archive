<?php

namespace App\Jav\Events;

use App\Jav\Models\Movie;

class MovieUpdated
{
    public function __construct(public Movie $movie)
    {
    }
}
