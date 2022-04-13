<?php

namespace App\Flickr\Listeners;

use App\Flickr\Events\FlickrContactCreated;
use App\Flickr\Models\FlickrProcess;
use Illuminate\Events\Dispatcher;

class FlickrContactEventSubscriber
{
    public function onFlickrContactCreated(FlickrContactCreated $event)
    {
        $processes = [
            FlickrProcess::STEP_PEOPLE_INFO,
            FlickrProcess::STEP_PEOPLE_PHOTOS,
            FlickrProcess::STEP_PHOTOSETS_LIST,
            FlickrProcess::STEP_PEOPLE_FAVORITE_PHOTOS,
        ];

        foreach ($processes as $process) {
            $event->contact->processes()->create([
                'step' => $process,
            ]);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            [FlickrContactCreated::class],
            [self::class, 'onFlickrContactCreated']
        );
    }
}
