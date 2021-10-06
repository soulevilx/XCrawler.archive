<?php

namespace App\Flickr\Listeners;

use App\Core\Models\RequestFailed;
use App\Flickr\Events\FlickrRequestFailed;
use App\Flickr\Services\FlickrService;
use Illuminate\Events\Dispatcher;

class FlickrEventSubscriber
{
    public function handleFlickrRequestFailed(FlickrRequestFailed $event)
    {
        RequestFailed::create([
            'service' => FlickrService::SERVICE,
            'endpoint' => 'https://api.flickr.com/services/rest/',
            'path' => $event->path,
            'params' => $event->params,
            'message' => $event->message
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
            [FlickrRequestFailed::class],
            self::class . '@handleFlickrRequestFailed'
        );
    }
}
