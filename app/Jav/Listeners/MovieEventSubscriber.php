<?php

namespace App\Jav\Listeners;

use App\Jav\Events\MovieCreated;
use App\Jav\Events\Onejav\OnejavDownloadCompleted;
use App\Jav\Services\Movie\MovieService;
use Illuminate\Events\Dispatcher;

class MovieEventSubscriber
{
    public function onOnejavDownloadCompleted(OnejavDownloadCompleted $event)
    {
        $event->onejav->movie->requestDownload()->delete();
    }

    public function indexMovie(MovieCreated $event)
    {
        app(MovieService::class)->createIndex($event->movie);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher  $events
     *
     * @return void
     */
    public function subscribe($events): void
    {
        /**
         * @TODO Handle MovieUpdated than update movie index
         */
        $events->listen(
            [
                MovieCreated::class,
            ],
            self::class.'@indexMovie'
        );

        $events->listen(
            [
                OnejavDownloadCompleted::class
            ],
            self::class.'@onOnejavDownloadCompleted'
        );
    }
}
