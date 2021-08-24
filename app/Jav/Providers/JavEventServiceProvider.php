<?php

namespace App\Jav\Providers;

use App\Jav\Listeners\MovieEventSubscriber;
use App\Jav\Models\R18;
use App\Jav\Observers\R18Observer;
use App\Providers\EventServiceProvider;

class JavEventServiceProvider extends EventServiceProvider
{
    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        MovieEventSubscriber::class,
    ];

    public function boot()
    {
        parent::boot();

        R18::observe(R18Observer::class);
    }
}
