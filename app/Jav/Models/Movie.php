<?php

namespace App\Jav\Models;

use App\Core\Models\Traits\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property Collection|Performer[] $performers
 * @property Collection|Genre[]     $genres
 */
class Movie extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        'is_downloadable',
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
        'is_downloadable' => 'boolean',
    ];

    public static function findByDvdId(string $dvdId)
    {
        return self::where('dvd_id', $dvdId)->first();
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genres')->withTimestamps();
    }

    public function performers(): BelongsToMany
    {
        return $this->belongsToMany(Performer::class, 'movie_performers')->withTimestamps();
    }

//    public function wordpress()
//    {
//        return $this->hasOne(WordPressPost::class, 'title', 'dvd_id');
//    }
//
//    public function onejav(): HasOne
//    {
//        return $this->hasOne(Onejav::class, 'dvd_id', 'dvd_id');
//    }
//
//    public function r18(): HasOne
//    {
//        return $this->hasOne(R18::class, 'dvd_id', 'dvd_id');
//    }
}
