<?php

namespace App\Jav\Events;

use App\Jav\Models\Genre;

class GenreCreated
{
    public function __construct(protected Genre $genre)
    {
    }
}
