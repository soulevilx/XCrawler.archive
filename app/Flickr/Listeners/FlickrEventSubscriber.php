<?php

namespace App\Flickr\Listeners;

use App\Core\Models\ClientRequest;
use App\Core\Models\RequestFailed;
use App\Flickr\Events\Errors\UserDeleted;
use App\Flickr\Events\FlickrRequestFailed;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Services\FlickrService;
use Illuminate\Events\Dispatcher;

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
