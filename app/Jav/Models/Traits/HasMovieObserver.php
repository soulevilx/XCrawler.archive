<?php

namespace App\Jav\Models\Traits;

use App\Jav\Services\Movie\Observers\MovieObserver;

trait HasMovieObserver
{
    protected static function bootHasMovieObserver()
    {
        static::observe(MovieObserver::class);
    }
}
