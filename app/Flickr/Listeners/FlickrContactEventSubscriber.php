<?php

namespace App\Flickr\Listeners;

use App\Core\Models\State;
use App\Flickr\Events\FlickrContactCreated;
use App\Flickr\Models\FlickrContactProcess;
use Illuminate\Events\Dispatcher;

class FlickrContactEventSubscriber
{
    public function onFlickrContactCreated(FlickrContactCreated $event)
    {
        $event->contact->process()->create([
            'step' => FlickrContactProcess::STEP_PEOPLE_INFO,
            'state_code' => State::STATE_INIT,
        ]);

        $event->contact->process()->create([
            'step' => FlickrContactProcess::STEP_PHOTOSETS_LIST,
            'state_code' => State::STATE_INIT,
        ]);
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
            self::class . '@onFlickrContactCreated'
        );
    }
}
