<?php

namespace App\Jav\Listeners;

use App\Jav\Events\Onejav\OnejavDailyCompleted;
use App\Jav\Events\Onejav\OnejavReleaseCompleted;
use App\Jav\Notifications\OnejavCompletedNotification;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Notification;

class OnejavEventSubscriber
{
    public function handleOnejavCompleted(OnejavDailyCompleted|OnejavReleaseCompleted $event)
    {
        Notification::route('slack', env('SLACK_NOTIFICATION'))
            ->notify(new OnejavCompletedNotification($event));
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
