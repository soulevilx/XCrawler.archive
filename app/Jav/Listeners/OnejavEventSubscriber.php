<?php

namespace App\Jav\Listeners;

use App\Jav\Events\Onejav\OnejavDailyCompleted;
use App\Jav\Events\Onejav\OnejavReleaseCompleted;
use Illuminate\Events\Dispatcher;
use Illuminate\Notifications\Notifiable;

class OnejavEventSubscriber
{
    public function handleOnejavCompleted(OnejavDailyCompleted|OnejavReleaseCompleted $event)
    {
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            [
                OnejavDailyCompleted::class,
                OnejavReleaseCompleted::class,
            ],
            self::class . '@handleOnejavCompleted'
        );
    }
}
