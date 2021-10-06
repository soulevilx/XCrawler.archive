<?php

namespace App\Jav\Events;

use App\Jav\Models\Movie;

/**
 * This event trigger whenever movie create completed with Genres & Performers
 */
class MovieCreated
{
    public function __construct(public Movie $movie)
    {
    }
}
