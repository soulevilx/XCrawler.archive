<?php

namespace App\Core\Listeners;

use App\Core\Events\ClientRequested;
use App\Core\Models\ClientRequest;
use App\Core\Notifications\ClientRequestFailedNotification;
use App\Core\Services\ApplicationService;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Jooservices\XcrawlerClient\Response\DomResponse;

class ClientRequestEventSubscriber
{
    public function handleClientRequested(ClientRequested $event)
    {
        /**
         * @var DomResponse $response
         */
        $response = $event->response;

        ClientRequest::create([
            'service' => $event->service,
            'base_uri' => config('services' . '.' . $event->service . '.base_url'),
            'endpoint' => $response->getEndpoint() ?? $event->endpoint,
            'payload' => $event->payload,
            'body' => Str::substr((trim($response->getBody())), 0, 100),
            'is_succeed' => $response->isSuccessful(),
            //'messages' => is_array($response) ? $response['message'] ?? null : $response->getResponseMessage(),
            //'code' => $response['code'] ?? null,
        ]);

        if (!$response->isSuccessful() && ApplicationService::getConfig('core', 'enable_slack_notification', false)) {
            Notification::route('slack', config('services.slack.notifications'))
                ->notify(new ClientRequestFailedNotification($response));
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
            [ClientRequested::class],
            self::class . '@handleClientRequested'
        );
    }
}
