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

class MovieCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Movie $movie)
    {
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
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
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

                if ($this->movie->onejav) {
                    $attachment->action('onejav', Onejav::BASE_URL.$this->movie->onejav->url);
                    $attachment->action('download', app(UrlGenerator::class)->route('onejav.download', $this->movie->onejav->dvd_id));
                }

                if ($this->movie->r18) {
                    $attachment->action('r18', $this->movie->r18->url);
                }
            });
    }
}
