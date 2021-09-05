<?php

namespace App\Jav\Tests\Unit\Listeners;

use App\Core\Services\ApplicationService;
use App\Jav\Events\MovieCreated;
use App\Jav\Models\Movie;
use App\Jav\Notifications\MovieCreatedNotification;
use App\Jav\Tests\JavTestCase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

class MovieEventSubscriberTest extends JavTestCase
{
    public function testMovieCreatedWillTriggerNotification()
    {
        Notification::fake();

        $movie = Movie::factory()->create();
        Event::dispatch(new MovieCreated($movie));

        Notification::assertSentTo(
            new AnonymousNotifiable(),
            MovieCreatedNotification::class,
            function ($notification) use ($movie) {
                return $notification->movie->is($movie);
            }
        );
    }

    public function testMovieCreatedWillNotTriggerNotification()
    {
        Notification::fake();

        ApplicationService::setConfig(
            'jav',
            'enable_notification',
            false
        );
        $movie = Movie::factory()->create();
        Event::dispatch(new MovieCreated($movie));

        Notification::assertNotSentTo(
            new AnonymousNotifiable(),
            MovieCreatedNotification::class,
        );
    }
}
