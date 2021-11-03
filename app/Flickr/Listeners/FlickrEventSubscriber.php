<?php

namespace App\Flickr\Listeners;

use App\Core\Models\ClientRequest;
use App\Core\Models\RequestFailed;
use App\Flickr\Events\Errors\UserDeleted;
use App\Flickr\Events\FlickrRequestFailed;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Services\Flickr\People;
use App\Flickr\Services\FlickrService;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Event;

class FlickrEventSubscriber
{
    public function handleFlickrRequestFailed(FlickrRequestFailed $event)
    {
        $response = $event->response;

        ClientRequest::create([
            'service' => FlickrService::SERVICE,
            'base_uri' => 'https://api.flickr.com/services/rest/',
            'endpoint' => $event->path,
            'payload' => $event->params,
            'body' => null,
            'messages' => $response['message'] ?? null,
            'code' => $response['code'] ?? null,
            'is_succeed' => false,
        ]);

        $pathMaps = [
            'flickr.people.getInfo' => People::class,
        ];

        if (!empty($response)) {
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
    }

    public function handleUserDeleted(UserDeleted $event)
    {
        if (!$contact = FlickrContact::where('nsid', $event->params['user_id'] ?? null)->first()) {
            return;
        }

//        $contact->process()->delete();
//
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
            [UserDeleted::class],
            self::class . '@handleUserDeleted'
        );
    }
}
