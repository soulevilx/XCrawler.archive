<?php

namespace App\Flickr\Listeners;

use App\Core\Models\State;
use App\Flickr\Events\Errors\UserDeleted;
use App\Flickr\Events\Errors\UserNotFound;
use App\Flickr\Events\FlickrDownloadCompleted;
use App\Flickr\Events\FlickrRequestFailed;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Services\Flickr\People;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Event;

class FlickrEventSubscriber
{
    public function handleFlickrRequestFailed(FlickrRequestFailed $event)
    {
        $response = $event->response;
        $pathMaps = [
            'flickr.people.getInfo' => People::class,
            'flickr.people.getPhotos' => People::class,
        ];

        if (empty($response)) {
            return;
        }

        foreach (array_keys($pathMaps) as $key) {
            if ($event->path !== $key) {
                continue;
            }

            $targetClass = $pathMaps[$key];

            if (!isset($response['code']) || !isset($targetClass::EVENT_MAPS[$response['code']])) {
                continue;
            }

            $eventClass = $targetClass::EVENT_MAPS[$response['code']];
            if (!class_exists($eventClass)) {
                continue;
            }

            Event::dispatch(new $eventClass($event->path, $event->params));
        }
    }

    public function cleanUpUser(UserDeleted|UserNotFound $event)
    {
        if (!$contact = FlickrContact::where('nsid', $event->params['user_id'] ?? null)->first()) {
            return;
        }

        $contact->processes()->delete();
//
        foreach ($contact->albums as $album) {
            $album->processes()->delete();
        }

        $contact->delete();
    }

    public function handleDownloadCompleted(FlickrDownloadCompleted $event)
    {
        $event->download->setState(State::STATE_COMPLETED);
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
            [
                UserDeleted::class,
                UserNotFound::class,
            ],
            self::class . '@cleanUpUser'
        );

        $events->listen(
            [FlickrDownloadCompleted::class],
            [
                self::class, 'handleDownloadCompleted',
            ],
        );
    }
}
