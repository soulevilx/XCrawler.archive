<?php

namespace App\Jav\Repositories;

use App\Core\Repositories\Traits\HasDefaultRepository;
use App\Jav\Events\PerformerCreated;
use App\Jav\Models\Movie;
use App\Jav\Models\Performer;
use Illuminate\Support\Facades\Event;

class PerformerRepository
{
    use HasDefaultRepository;

    private Movie $movie;

    public function __construct(public Performer $model)
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
}
