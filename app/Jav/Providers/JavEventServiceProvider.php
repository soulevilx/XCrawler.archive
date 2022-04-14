<?php

namespace App\Jav\Providers;

use App\Jav\Listeners\MovieEventSubscriber;
use App\Jav\Listeners\OnejavEventSubscriber;
use App\Jav\Listeners\XCityIdolEventSubscriber;
use App\Jav\Listeners\XCityVideoEventSubscriber;
use App\Jav\Models\Onejav;
use App\Jav\Models\R18;
use App\Jav\Models\XCityIdol;
use App\Jav\Models\XCityVideo;
use App\Jav\Observers\OnejavObserver;
use App\Jav\Observers\R18Observer;
use App\Jav\Observers\XCityIdolObserver;
use App\Jav\Observers\XCityVideoObserver;
use App\Providers\EventServiceProvider;

class JavEventServiceProvider extends EventServiceProvider
{
    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        OnejavEventSubscriber::class,
        MovieEventSubscriber::class,
        XCityIdolEventSubscriber::class,
        XCityVideoEventSubscriber::class
    ];

    public function boot()
    {
        parent::boot();

        R18::observe(R18Observer::class);
        XCityIdol::observe(XCityIdolObserver::class);
        XCityVideo::observe(XCityVideoObserver::class);
        Onejav::observe(OnejavObserver::class);
    }
}
