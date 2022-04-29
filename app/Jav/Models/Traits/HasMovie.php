<?php

namespace App\Jav\Models\Traits;

use App\Jav\Models\Movie;
use App\Jav\Services\Movie\Observers\MovieObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $dvd_id
 * @property string $name
 * @property array $genres
 * @property array $performers
 * @property-read  Movie $movie
 */
trait HasMovie
{
    public static function bootHasMovie()
    {
        static::observe(MovieObserver::class);
    }

    public function initializeHasMovie()
    {
        $this->mergeFillable(['dvd_id']);
        $this->mergeCasts(['dvd_id' => 'string']);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'dvd_id';
    }

    public function getDvdId(): ?string
    {
        return $this->dvd_id ?? null;
    }

    public function getContentId(): ?string
    {
        return $this->content_id ?? null;
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

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class, 'dvd_id', 'dvd_id');
    }

    public static function findByDvdId(string $dvdId)
    {
        return self::where(['dvd_id' => $dvdId])->first();
    }
}
