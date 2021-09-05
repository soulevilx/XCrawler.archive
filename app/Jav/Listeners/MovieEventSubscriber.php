<?php

namespace App\Jav\Listeners;

use App\Core\Models\State;
use App\Jav\Events\MovieCreated;
use App\Jav\Notifications\MovieCreatedNotification;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Notification;

class MovieEventSubscriber
{
    public function onMovieCreated(MovieCreated $event)
    {
        $movie = $event->movie;

        $movie->wordpress()->firstOrCreate([
            'title' => $event->movie->dvd_id,
        ], [
            'state_code' => State::STATE_INIT,
        ]);

        if (!config('services.jav.enable_notification', true)) {
            return;
        }

        Notification::route('slack', config('services.slack.notifications'))
            ->notify(new MovieCreatedNotification($event->movie));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            [MovieCreated::class],
            self::class . '@onMovieCreated'
        );
    }
}
