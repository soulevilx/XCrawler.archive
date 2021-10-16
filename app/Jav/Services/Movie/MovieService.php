<?php

namespace App\Jav\Services\Movie;

use App\Core\Models\State;
use App\Core\Models\WordPressPost;
use App\Jav\Events\MovieCreated;
use App\Jav\Models\Genre;
use App\Jav\Models\Interfaces\MovieInterface;
use App\Jav\Models\Movie;
use App\Jav\Models\Performer;
use App\Jav\Models\R18;
use Illuminate\Support\Facades\Event;

class MovieService
{
    /**
     * @var Movie $movie
     */
    public $movie;

    public function create(MovieInterface $model): Movie
    {
        /**
         * R18 without DvdID
         */
        $data = $model->toArray();
        $data['dvd_id'] = $model->getDvdId();
        $data['name'] = $model->getName();

        if ($model instanceof R18) {
            $data['content_id'] = $model->getContentId();
            if ($movie = Movie::findBy('content_id', $model->getContentId())) {
                /**
                 * Case 1 : Check by content id
                 */
                $this->movie = $movie;
            } elseif ($model->getDvdId() && $movie = Movie::findBy('dvd_id', $model->getDvdId())) {
                /**
                 * Case 2 : Check by dvd_id
                 */
                $this->movie = $movie;
            }

            if ($this->movie) {
                $this->movie->update($data);
            } else {
                /**
                 * R18 always had content_id
                 */
                $this->movie = Movie::create($data);
            }
        } else {
            $this->movie = Movie::updateOrCreate([
                'dvd_id' => $model->getDvdId(),
            ], $data);
        }

        $this->createGenres($this->movie, $model->getGenres());
        $this->createPerformers($this->movie, $model->getPerformers());

        /**
         * Only dispatch event for created case
         */
        if ($this->movie->wasRecentlyCreated) {
            Event::dispatch(new MovieCreated($this->movie));
        }

        return $this->movie;
    }

    public function createGenres(Movie $movie, array $genres = []): array
    {
        $genreIds = [];
        foreach ($genres as $genre) {
            $genreIds[] = Genre::firstOrCreate([
                'name' => $genre,
            ])->id;
        }

        $movie->genres()->syncWithoutDetaching($genreIds);

        return $genreIds;
    }

    public function createPerformers(Movie $movie, array $performers = []): array
    {
        $actorIds = [];
        foreach ($performers as $actor) {
            $actorIds[] = Performer::firstOrCreate([
                'name' => $actor,
            ])->id;
        }

        $movie->performers()->syncWithoutDetaching($actorIds);

        return $actorIds;
    }

    public function createWordPressPost(Movie $movie, bool $force = false): ?WordPressPost
    {
        $this->movie = $movie;

        // Already posted to WordPress
        if (!$force && $this->movie->wordpress()->where('state_code', State::STATE_COMPLETED)->exists()) {
            session()->flash('message', ['message' => 'This movie already posted on WordPress', 'type' => 'warning']);
            return null;
        }

        return $this->movie->wordpress()->create([
            'title' => $this->movie->dvd_id ?? $this->movie->content_id,
            'state_code' => State::STATE_INIT,
        ]);
    }

    public function requestDownload(Movie $movie)
    {
        if (!$movie->requestDownload()->exists()) {
            return $movie->requestDownload()->create();
        }

        session()->flash('message', ['message' => 'Movie download in queued', 'type' => 'info']);
    }
}
