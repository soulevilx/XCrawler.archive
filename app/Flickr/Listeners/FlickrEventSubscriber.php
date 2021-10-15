<?php

namespace App\Flickr\Listeners;

use App\Core\Models\RequestFailed;
use App\Flickr\Events\ErrorUserDeleted;
use App\Flickr\Events\FlickrRequestFailed;
use App\Flickr\Models\FlickrContact;
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

    public function handleUserDeleted(ErrorUserDeleted $event)
    {
        $contact = FlickrContact::where('nsid', $event->nsid)->first();
        $contact->process()->delete();

        foreach ($contact->albums as $album) {
            $album->process()->delete();
        }

        $contact->delete();
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

        $events->listen(
            [ErrorUserDeleted::class],
            self::class . '@handleUserDeleted'
        );
    }
}
