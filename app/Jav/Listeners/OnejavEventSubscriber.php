<?php

namespace App\Jav\Listeners;

use App\Jav\Events\Onejav\OnejavDailyCompleted;
use Illuminate\Events\Dispatcher;

class OnejavEventSubscriber
{
    public function onOnejavDailyCompleted(OnejavDailyCompleted $event)
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
            [OnejavDailyCompleted::class],
            self::class . '@onOnejavDailyCompleted'
        );
    }
}
