<?php

namespace App\Jav\Notifications;

use App\Jav\Models\Movie;
use App\Jav\Models\Onejav;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Routing\UrlGenerator;
use Spatie\RateLimitedMiddleware\RateLimited;

class MovieCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Movie $movie)
    {
    }

    public function middleware()
    {
        if ('testing' === config('app.env')) {
            return [];
        }

        $rateLimitedMiddleware = (new RateLimited())
            ->allow(2) // Allow 2 job
            ->everySecond() // In second
            ->releaseAfterMinutes(1); // Release back to pool

        return [$rateLimitedMiddleware];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return $notifiable->toArray();
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage())
            ->success()
            ->content($this->movie->name ?? $this->movie->dvd_id)
            ->from('New movie', ':clapper:')
            ->attachment(function (SlackAttachment $attachment) {
                $attachment
                    ->image($this->movie->cover)
                    ->fields([
                        'Performers' => $this->movie->performers->implode('name', ', '),
                        'Genres' => $this->movie->genres->implode('name', ', '),
                        'Size' => $this->movie->onejav?->size
                    ]);

                $attachment->action(
                    'Movie',
                    app(UrlGenerator::class)->route(
                        'movie.show',
                        ['movie' => $this->movie]
                    ),
                    'danger'
                );
            });
    }
}
