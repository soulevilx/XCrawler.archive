<?php

namespace App\Jav\Models\Traits;

use App\Jav\Models\Movie;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $dvd_id
 * @property string $name
 * @property array  $genres
 * @property array  $performers
 * @property Movie  $movie
 */
trait HasDefaultMovie
{
    public function getDvdId(): ?string
    {
        return $this->dvd_id ?? null;
    }

    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    public function getGenres(): array
    {
        return $this->genres ?? [];
    }

    public function getPerformers(): array
    {
        return $this->performers ?? [];
    }

    public function isDownloadable(): bool
    {
        return false;
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class, 'dvd_id', 'dvd_id');
    }

    public static function findByDvdId(Builder $builder, string $dvdId)
    {
        return $builder->where(['dvd_id' => $dvdId]);
    }
}
