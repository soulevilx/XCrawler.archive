<?php

namespace App\Jav\Listeners;

use App\Jav\Events\XCityVideoCompleted;
use App\Jav\Models\Movie;
use Illuminate\Events\Dispatcher;

class XCityVideoEventSubscriber
{
    public function onVideoCompleted(XCityVideoCompleted $event)
    {
        $model = $event->model;
        Movie::firstOrCreate([
            'dvd_id' => $model->dvd_id,
        ], $model->toArray());
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            [XCityVideoCompleted::class],
            self::class.'@onVideoCompleted'
        );
    }
}
