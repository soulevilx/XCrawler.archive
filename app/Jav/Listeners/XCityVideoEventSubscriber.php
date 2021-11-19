<?php

namespace App\Jav\Listeners;

use App\Jav\Events\MovieCreated;
use App\Jav\Events\XCityVideoCompleted;
use App\Jav\Models\Genre;
use App\Jav\Models\Movie;
use App\Jav\Models\Performer;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Event;

class XCityVideoEventSubscriber
{
    public function onVideoCompleted(XCityVideoCompleted $event)
    {
        $model = $event->model;
        $movie = Movie::firstOrCreate([
            'dvd_id' => $model->dvd_id,
        ], $model->toArray());

        $genreIds = [];
        if ($model->genres) {
            foreach ($model->genres as $genre) {
                $genreIds[] = Genre::firstOrCreate([
                    'name' => $genre,
                ])->id;
            }
        }

        $actorIds = [];
        if ($model->actresses) {
            foreach ($model->actresses as $actor) {
                $actorIds[] = Performer::firstOrCreate([
                    'name' => $actor,
                ])->id;
            }
        }

        $movie->genres()->syncWithoutDetaching($genreIds);
        $movie->performers()->syncWithoutDetaching($actorIds);

        Event::dispatch(new MovieCreated($movie));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            [XCityVideoCompleted::class],
            self::class . '@onVideoCompleted'
        );
    }
}
