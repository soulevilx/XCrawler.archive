<?php

namespace App\Jav\Tests\Unit\Listeners;

use App\Jav\Events\MovieCreated;
use App\Jav\Models\Movie;
use App\Jav\Notifications\MovieCreatedNotification;
use App\Jav\Tests\JavTestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

class MovieEventSubscriberTest extends JavTestCase
{
    public function testMovieCreatedWillTriggerNotification()
    {
        $movie = Movie::factory()->create();
        Event::dispatch(new MovieCreated($movie));

        Notification::assertSentTo(
            $movie,
            MovieCreatedNotification::class,
            function ($notification) use ($movie) {
                return $notification->movie->is($movie);
            }
        );
    }
}
