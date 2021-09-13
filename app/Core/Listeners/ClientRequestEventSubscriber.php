<?php

namespace App\Core\Listeners;

use App\Core\Events\ClientRequested;
use App\Core\Notifications\ClientRequestFailedNotification;
use App\Core\Services\ApplicationService;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Notification;

class ClientRequestEventSubscriber
{
    public function handleClientRequested(ClientRequested $event)
    {
        $response = $event->response;

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
            self::class.'@handleClientRequested'
        );
    }
}
