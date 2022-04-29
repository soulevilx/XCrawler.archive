<?php

namespace App\Jav\Services\Movie;

use App\Jav\Events\MovieCreated;
use App\Jav\Events\MovieUpdated;
use App\Jav\Models\Interfaces\MovieInterface;
use App\Jav\Models\Movie;
use App\Jav\Models\R18;
use App\Jav\Repositories\MovieRepository;
use Illuminate\Support\Facades\Event;

class MovieService
{
    public function __construct(protected MovieRepository $repository)
    {
    }

    public function create(MovieInterface $model): Movie
    {
        /**
         * R18 without DvdID
         */
        $modelAttributes = $model->toArray();
        $modelAttributes['dvd_id'] = $model->getDvdId();
        $modelAttributes['name'] = $model->getName();

        if ($model instanceof R18) {
            $modelAttributes['content_id'] = $model->getContentId();
            $movie = $this->repository->getByContentIdOrDvdid($model->getContentId(), $model->getDvdId());

            if (isset($movie)) {
                $movie->update($modelAttributes);
            } else {
                /**
                 * R18 always had content_id
                 */
                $movie = $this->repository->create($modelAttributes);
            }
        } else {
            /**
             * Most case we don't have content_id than use dvd_id instead
             */
            $movie = $this->repository
                ->updateOrCreateByDvdId($model->getDvdId(), $modelAttributes)
                ->getModel();
        }

        $this->repository->addGenres($model->getGenres());
        $this->repository->addPerformers($model->getPerformers());

        if ($movie->wasRecentlyCreated) {
            Event::dispatch(new MovieCreated($movie));
        } else {
            Event::dispatch(new MovieUpdated($movie));
        }

        /**
         * Trigger when downloadable
         */

        return $movie;
    }

    public function requestDownload(Movie $movie)
    {
        if ($movie->requestDownload()->exists()) {
            return false;
        }

        return $movie->requestDownload()->create();
    }
}
