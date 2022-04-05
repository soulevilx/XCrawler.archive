<?php

namespace App\Jav\Repositories;

use App\Core\Repositories\AbstractRepository;
use App\Jav\Models\Movie;

class MovieRepository extends AbstractRepository
{
    private GenreRepository $genreRepository;
    private PerformerRepository $performerRepository;

    public function __construct(protected Movie $model)
    {
        $this->genreRepository = app(GenreRepository::class);
        $this->performerRepository = app(PerformerRepository::class);
    }

    public function getByContentIdOrDvdid(?string $contentId, ?string $dvdId): ?Movie
    {
        if (!$model = $this->model->where('content_id', $contentId)->first()) {
            $model = $this->model->where('dvd_id', $dvdId)->first();
        }

        if (!$model) {
            return null;
        }

        $this->model = $model;
        return $model;
    }

    public function updateOrCreateByDvdId(string $dvdId, array $attributes): self
    {
        $this->model = Movie::updateOrCreate([
            'dvd_id' => $dvdId,
        ], $attributes);

        return $this;
    }

    public function addGenres(array $genres)
    {
        $this->genreRepository->setMovie($this->model);
        $this->genreRepository->sync($genres);
    }

    public function addPerformers(array $performers)
    {
        $this->performerRepository->setMovie($this->model);
        $this->performerRepository->sync($performers);
    }
}
