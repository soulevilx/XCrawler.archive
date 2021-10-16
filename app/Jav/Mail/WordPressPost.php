<?php

namespace App\Jav\Mail;

use App\Jav\Models\Movie;
use App\Jav\Services\R18Service;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

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
        if ($r18 = $this->movie->r18) {
            app(R18Service::class)->refetch($r18);
            $this->movie->refresh();
        }

        $genres = implode(', ', array_map(
            function ($genre) {
                return '<a href="https://soulevil.com/tag/' . Str::slug($genre) . '">' . $genre . '</a>';
            },
            $this->movie->genres()->pluck('name')->toArray()
        ));
        $performers = implode(', ', array_map(
            function ($performer) {
                return '<a href="https://soulevil.com/tag/' . Str::slug($performer) . '">' . $performer . '</a>';
            },
            $this->movie->performers()->pluck('name')->toArray()
        ));

        return $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->to(config('mail.to.address'), config('mail.to.name'))
            ->view('emails.movie')
            ->with([
                'movie' => $this->movie,
                'htmlGenres' => $genres,
                'htmlPerformers' => $performers,
                'genres' => implode(', ', $this->movie->genres()->pluck('name')->toArray()),
                'performers' => implode(', ', $this->movie->performers()->pluck('name')->toArray()),
                'channels' => implode(', ', $this->movie->channels ?? []),
                'onejav' => $this->movie->onejav,
                'r18' => $r18,
                'publicize' => 'off',
                'status' => 'draft'
            ]);
    }
}
