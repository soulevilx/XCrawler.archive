<?php

namespace App\Jav\Listeners;

use App\Jav\Events\MovieCreated;
use App\Jav\Events\Onejav\OnejavDownloadCompleted;
use App\Jav\Models\MovieIndex;
use App\Jav\Notifications\MovieCreatedNotification;
use Illuminate\Events\Dispatcher;

class MovieEventSubscriber
{
    public function onOnejavDownloadCompleted(OnejavDownloadCompleted $event)
    {
        $event->onejav->movie->requestDownload()->delete();
    }

    public function onMovieCreated(MovieCreated $event)
    {
        $movieData = $event->movie->toArray();
        unset($movieData['id']);
        $movieData['genres'] = $event->movie->genres()->pluck('name')->toArray();
        $movieData['performers'] = $event->movie->performers()->pluck('name')->toArray();
        MovieIndex::create($movieData);

        $event->movie->notify(new MovieCreatedNotification($event->movie));
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
