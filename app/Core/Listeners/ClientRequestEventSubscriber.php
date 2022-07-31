<?php

namespace App\Core\Listeners;

use App\Core\Events\Client\ClientRequested;
use App\Core\Events\Client\ClientRequestFailed;
use App\Core\Models\ClientRequest;
use App\Core\Services\Facades\Application;
use App\Flickr\Events\FlickrRequested;
use App\Flickr\Services\FlickrService;
use Illuminate\Events\Dispatcher;

class ClientRequestEventSubscriber
{
    public function handleClientRequest(ClientRequested|ClientRequestFailed $event)
    {
        $data = [
            'service' => $event->service,
            'base_uri' => Application::getSetting($event->service, 'base_url'),
            'method' => $event->method,
            'options' => $event->options,
            'endpoint' => $event->endpoint,
            'payload' => $event->payload,
        ];

        if ($event instanceof ClientRequested) {
            $data['is_succeed'] = $event->response?->isSuccessful();
        }

        if ($event instanceof ClientRequestFailed) {
            $data['is_succeed'] = false;
            $data['response'] = $event?->exception->getMessage();
        }

        ClientRequest::create($data);
    }

    public function handleFlickrRequested(FlickrRequested $event)
    {
        ClientRequest::create([
            'service' => FlickrService::SERVICE_NAME,
            'method' => $event->method,
            'options' => null,
            'endpoint' => $event->path,
            'payload' => $event->params,
            'is_succeed' => $event->isSucceed(),
            'response' => $event->jsonResponse['message'] ?? null,
        ]);
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
        $events->listen([
            ClientRequested::class,
            ClientRequestFailed::class,
        ], self::class.'@handleClientRequest');

        $events->listen([
            FlickrRequested::class,
        ], self::class.'@handleFlickrRequested');
    }
}
