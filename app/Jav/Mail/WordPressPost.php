<?php

namespace App\Jav\Mail;

use App\Jav\Models\Movie;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WordPressPost extends Mailable
{
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Movie $movie)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->to(config('mail.to.address'), config('mail.to.name'))
            ->view('emails.movie')
            ->with([
                'movie' => $this->movie,
                'genres' => implode(', ', $this->movie->genres()->pluck('name')->toArray()),
                'performers' => implode(', ', $this->movie->performers()->pluck('name')->toArray()),
                'channels' => implode(', ', $this->movie->channels ?? []),
                'onejav' => $this->movie->onejav,
                'r18' => $this->movie->r18,
                'sample' => $this->movie->r18->sample['high'] ?? null
            ]);
    }
}
