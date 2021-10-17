<?php

namespace App\Jav\Models;

use App\Core\Models\Download;
use App\Core\Models\Traits\HasFactory;
use App\Jav\Models\Interfaces\MovieInterface;
use App\Jav\Models\Traits\HasDefaultMovie;
use App\Jav\Models\Traits\HasMovieObserver;
use App\Jav\Services\OnejavService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $url
 * @property string $torrent
 * @property-read Download $downloads
 */
class Onejav extends Model implements MovieInterface
{
    use HasFactory;
    use SoftDeletes;
    use HasMovieObserver;
    use HasDefaultMovie;

    public const SERVICE = 'onejav';
    public const BASE_URL = 'https://onejav.com';
    public const DAILY_FORMAT = 'Y/m/d';

    protected $table = 'onejav';

    protected $fillable = [
        'url',
        'cover',
        'dvd_id',
        'size',
        'date',
        'genres',
        'performers',
        'description',
        'torrent',
    ];

    protected $casts = [
        'url' => 'string',
        'cover' => 'string',
        'dvd_id' => 'string',
        'size' => 'float',
        'date' => 'datetime:Y-m-d',
        'genres' => 'array',
        'performers' => 'array',
        'description' => 'string',
        'torrent' => 'string',
        'updated_at' => 'datetime:Y-m-d H:m:s',
        'created_at' => 'datetime:Y-m-d H:m:s',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'dvd_id';
    }

    /**
     * Onejav have no state.
     */
    public function isCompletedState(): bool
    {
        return true;
    }

    public function downloads()
    {
        return $this->morphMany(Download::class, 'model');
    }
}
