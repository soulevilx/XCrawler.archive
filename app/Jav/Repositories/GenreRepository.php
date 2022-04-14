<?php

namespace App\Jav\Repositories;

use App\Core\Repositories\Traits\HasDefaultRepository;
use App\Jav\Events\GenreCreated;
use App\Jav\Models\Genre;
use App\Jav\Models\Movie;
use Illuminate\Support\Facades\Event;

class GenreRepository
{
    use HasDefaultRepository;

    private Movie $movie;

    public function __construct(public Genre $model)
    {
    }

    public function setMovie(Movie $movie)
    {
        $this->movie = $movie;
    }

    public function sync(array $genres)
    {
        foreach ($genres as $genre) {
            $genre = $this->model->firstOrCreate([
                'name' => $genre,
            ]);

            if ($genre->wasRecentlyCreated) {
                Event::dispatch(new GenreCreated($genre));
            }

            $this->movie->genres()->syncWithoutDetaching([$genre->id]);
        }
    }
}
