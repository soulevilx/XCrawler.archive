<?php

namespace App\Jav\Models;

use App\Core\Models\Traits\HasFactory;
use App\Core\Models\WordPressPost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

/**
 * @property string $name
 * @property string $cover
 * @property $series
 * @property string $dvd_id
 * @property string $content_id
 * @property string $label
 * @property string $studio
 * @property string $description
 * @property array $channels
 * @property array $gallery
 * @property array $images
 * @property array $sample
 * @property Collection|Performer[] $performers
 * @property Collection|Genre[] $genres
 * @property-read Onejav $onejav
 * @property-read R18 $r18
 * @property-read MorphOne|WordPressPost $wordpress
 */
class Movie extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;

    protected $table = 'movies';

    protected $fillable = [
        'name',
        'cover',
        'sales_date',
        'release_date',
        'content_id',
        'dvd_id',
        'description',
        'time',
        'director',
        'studio',
        'label',
        'channels',
        'series',
        'gallery',
        'images',
        'sample',
    ];

    protected $casts = [
        'name' => 'string',
        'cover' => 'string',
        'content_id' => 'string',
        'dvd_id' => 'string',
        'description' => 'string',
        'label' => 'string',
        'channels' => 'array',
        'gallery' => 'array',
        'images' => 'array',
        'sample' => 'array',
        'series' => 'array',
        'director' => 'string',
        'release_date' => 'datetime:Y-m-d',
    ];

    /**
     * Retrieve the model for a bound value.
     *
     * @param mixed $value
     * @param string|null $field
     * @return Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if (!$model = $this->where($field ?? $this->getKeyName(), $value)->first()) {
            $model = $this->where($field ?? 'dvd_id', $value)->first();
        }

        return $model;
    }

    public static function findBy(string $fieldName, string $value)
    {
        return self::where($fieldName, $value)->first();
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genres')->withTimestamps();
    }

    public function performers(): BelongsToMany
    {
        return $this->belongsToMany(Performer::class, 'movie_performers')->withTimestamps();
    }

    public function wordpress()
    {
        return $this->morphOne(WordPressPost::class, 'model');
    }

    public function onejav(): HasOne
    {
        return $this->hasOne(Onejav::class, 'dvd_id', 'dvd_id');
    }

    public function r18(): HasOne
    {
        return $this->hasOne(R18::class, 'content_id', 'content_id');
    }

    public function isDownloadable(): bool
    {
        return $this->onejav()->exists();
    }

    public function requestDownload(): MorphOne
    {
        return $this->morphOne(RequestDownload::class, 'model');
    }

    public function series()
    {
        return is_array($this->series) ? implode(', ', $this->series) : $this->series;
    }
}
