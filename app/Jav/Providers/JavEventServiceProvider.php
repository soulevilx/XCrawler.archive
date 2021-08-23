<?php

namespace App\Jav\Providers;

use App\Core\Providers\CoreEventServiceProvider;
use App\Jav\Models\R18;
use App\Jav\Observers\R18Observer;
use App\Providers\EventServiceProvider;

class JavEventServiceProvider extends EventServiceProvider
{
    public function boot()
    {
        parent::boot();

            R18::observe(R18Observer::class);
    }
}
