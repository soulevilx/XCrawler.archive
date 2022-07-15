<?php

namespace App\Jav\Listeners;

use App\Core\Services\Facades\Application;
use App\Jav\Events\Onejav\OnejavDailyCompleted;
use App\Jav\Events\Onejav\OnejavReleaseCompleted;
use App\Jav\Notifications\OnejavCompletedNotification;
use App\Jav\Services\OnejavService;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Notification;

class OnejavEventSubscriber
{
    public function handleOnejavCompleted(OnejavDailyCompleted|OnejavReleaseCompleted $event)
    {
        if (!Application::getBool(OnejavService::SERVICE_NAME, 'send_notifications', false)) {
            return;
        }

        Notification::route('slack', Application::getString('jav', 'slack_url'))
            ->notify(new OnejavCompletedNotification($event));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            [
                OnejavDailyCompleted::class,
                OnejavReleaseCompleted::class,
            ],
            self::class.'@handleOnejavCompleted'
        );
    }
}
