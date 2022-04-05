<?php

namespace App\Jav\Repositories;

use App\Core\Repositories\AbstractRepository;
use App\Jav\Events\PerformerCreated;
use App\Jav\Models\Movie;
use App\Jav\Models\Performer;
use Illuminate\Support\Facades\Event;

class PerformerRepository extends AbstractRepository
{
    private Movie $movie;

    public function __construct(protected Performer $model)
    {
    }

    public function setMovie(Movie $movie)
    {
        $this->movie = $movie;
    }

    public function sync(array $performers)
    {
        foreach ($performers as $performer) {
            $performer = $this->model->firstOrCreate([
                'name' => $performer,
            ]);

            if ($performer->wasRecentlyCreated) {
                Event::dispatch(new PerformerCreated($performer));
            }

            $this->movie->performers()->syncWithoutDetaching([$performer->id]);
        }
    }

    public function getModel()
    {
        return $this->model;
    }
}
