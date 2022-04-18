<?php

namespace App\Jav\Tests\Unit\Listeners;

use App\Core\Models\BaseMongo;
use App\Jav\Events\MovieCreated;
use App\Jav\Models\Genre;
use App\Jav\Models\Movie;
use App\Jav\Notifications\MovieCreatedNotification;
use App\Jav\Tests\JavTestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

class MovieEventSubscriberTest extends JavTestCase
{
    public function testMovieCreated()
    {
        /**
         * @var Movie $movie
         */
        $movie = Movie::factory()->create();
        $genre = Genre::factory()->create();
        $movie->genres()->syncWithoutDetaching($genre->id);

        Event::dispatch(new MovieCreated($movie));
        $this->assertDatabaseHas(
            'movies',
            [
                'dvd_id' => $movie->dvd_id,
                'description' => $movie->description,
                'genres' => [
                    $genre->name,
                ]
            ],
            BaseMongo::CONNECTION_NAME
        );

        Notification::assertSentTo(
            $movie,
            MovieCreatedNotification::class,
            function ($notification) use ($movie) {
                return $notification->movie->is($movie);
            }
        );
    }
}
