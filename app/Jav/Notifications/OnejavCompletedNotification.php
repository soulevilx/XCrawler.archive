<?php

namespace App\Jav\Notifications;

use App\Jav\Events\Onejav\OnejavDailyCompleted;
use App\Jav\Events\Onejav\OnejavReleaseCompleted;
use App\Jav\Services\OnejavService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use Spatie\RateLimitedMiddleware\RateLimited;

class OnejavCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public OnejavDailyCompleted|OnejavReleaseCompleted $event)
    {
    }

    public function middleware()
    {
        if ('testing' === config('app.env')) {
            return [];
        }

        $rateLimitedMiddleware = (new RateLimited)
            ->allow(2) // Allow 2 job
            ->everySecond() // In second
            ->releaseAfterMinutes(1); // Release back to pool

        return [$rateLimitedMiddleware];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $slackMessage = new SlackMessage();
        $slackMessage->success()
            ->content(
                $this->event instanceof OnejavDailyCompleted
                    ? 'Daily movies'
                    : 'Release movies'
            )
            ->from(Str::ucfirst(OnejavService::SERVICE_NAME), ':clapper:');

        foreach ($this->event->items as $item) {
            $slackMessage->attachment(function (SlackAttachment $attachment) use ($item) {
                $attachment
                    ->pretext($item->dvd_id)
                    ->fields([
                        'Performers' => implode(', ', $item->performers),
                        'Genres' => implode(', ', $item->genres),
                        'Size' => $item->size
                    ])
                    ->image($item->cover)
                    ->action($item->dvd_id, OnejavService::BASE_URL.$item->url)
                    ->footer($item->url);
            });
        }

        return $slackMessage;
    }
}
