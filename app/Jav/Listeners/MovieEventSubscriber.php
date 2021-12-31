<?php

namespace App\Jav\Listeners;

use App\Core\Services\ApplicationService;
use App\Jav\Events\MovieCreated;
use App\Jav\Events\OnejavDownloadCompleted;
use App\Jav\Notifications\MovieCreatedNotification;
use App\Jav\Services\Movie\MovieService;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Notification;

class MovieEventSubscriber
{
    public function onOnejavDownloadCompleted(OnejavDownloadCompleted $event)
    {
        $event->onejav->movie->requestDownload()->delete();
    }

    public function onMovieCreated(MovieCreated $event)
    {
        $enableNotification = ApplicationService::getConfig(
            'jav',
            'enable_notification',
            config('services.jav.enable_notification', true)
        );
        $enablePostToWordPress = ApplicationService::getConfig(
            'jav',
            'enable_post_to_wordpress',
            config('services.jav.enable_post_to_wordpress', true)
        );
        $movie = $event->movie;

        if ($enablePostToWordPress) {
            $service = app(MovieService::class);
            $service->createWordPressPost($movie);
        }

        if (!$enableNotification) {
            return;
        }

        Notification::route('slack', config('services.jav.slack_notifications'))
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

        $events->listen(
            [
                OnejavDownloadCompleted::class
            ],

            self::class . '@onOnejavDownloadCompleted'
        );
    }
}
