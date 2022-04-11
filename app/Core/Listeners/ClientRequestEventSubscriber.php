<?php

namespace App\Core\Listeners;

use App\Core\Events\Client\ClientPrepared;
use App\Core\Events\Client\ClientRequested;
use App\Core\Events\Client\ClientRequestFailed;
use App\Core\Models\ClientRequest;
use App\Core\Services\Facades\Application;
use Illuminate\Events\Dispatcher;

class ClientRequestEventSubscriber
{
    public function handleClientRequest(ClientPrepared|ClientRequested|ClientRequestFailed $event)
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
            $data['is_succeed'] = $event?->response?->isSuccessful();
        }

        if ($event instanceof ClientRequestFailed) {
            $data['is_succeed'] = $event?->response?->isSuccessful();
            $data['error'] = $event?->exception->getMessage();
        }

        ClientRequest::create($data);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen([
            ClientRequested::class,
            ClientRequestFailed::class,
        ], self::class . '@handleClientRequest');
    }
}
