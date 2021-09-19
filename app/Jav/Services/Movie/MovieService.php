<?php

namespace App\Jav\Services\Movie;

use App\Core\Models\State;
use App\Core\Models\WordPressPost;
use App\Jav\Events\MovieCreated;
use App\Jav\Models\Genre;
use App\Jav\Models\Interfaces\MovieInterface;
use App\Jav\Models\Movie;
use App\Jav\Models\Performer;
use Illuminate\Support\Facades\Event;

class MovieService
{
    public Movie $movie;

    public function create(MovieInterface $model)
    {
        // @var Movie $movie
        $this->movie = Movie::firstOrCreate([
            'dvd_id' => $model->getDvdId(),
        ], ['is_downloadable' => $model->isDownloadable()] + $model->toArray());

        $genreIds = [];
        foreach ($model->getGenres() as $genre) {
            $genreIds[] = Genre::firstOrCreate([
                'name' => $genre,
            ])->id;
        }

        $actorIds = [];
        foreach ($model->getPerformers() as $actor) {
            $actorIds[] = Performer::firstOrCreate([
                'name' => $actor,
            ])->id;
        }

        $this->movie->genres()->syncWithoutDetaching($genreIds);
        $this->movie->performers()->syncWithoutDetaching($actorIds);

        Event::dispatch(new MovieCreated($this->movie));
    }

    public function createWordPressPost(Movie $movie): ?WordPressPost
    {
        $this->movie = $movie;

        // Already posted to WordPress
        if ($this->movie->wordpress()->exists()) {
            return null;
        }

        return $this->movie->wordpress()->firstOrCreate([
            'title' => $this->movie->dvd_id,
        ], [
            'state_code' => State::STATE_INIT,
        ]);
    }
}
