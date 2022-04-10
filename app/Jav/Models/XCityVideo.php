<?php

namespace App\Jav\Models;

use App\Core\Models\Traits\HasFactory;
use App\Core\Models\Traits\HasStates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read Movie $movie
 */
class XCityVideo extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasStates;

    public const INDEX_URL = 'avod/list';
    public const PER_PAGE = 90;

    protected $table = 'xcity_videos';

    protected $fillable = [
        'name',
        'url',
        'cover',
        'sales_date',
        'release_date',
        'item_number',
        'dvd_id',
        'description',
        'running_time',
        'director',
        'studio',
        'marker',
        'label',
        'channel',
        'series',
        'gallery',
        'sample',
        'genres',
        'actresses',
        'favorite',
    ];

    protected $casts = [
        'name' => 'string',
        'url' => 'string',
        'cover' => 'string',
        'sales_date' => 'datetime:Y-m-d',
        'release_date' => 'datetime:Y-m-d',
        'item_number' => 'string',
        'dvd_id' => 'string',
        'description' => 'string',
        'label' => 'string',
        'channel' => 'string',
        'marker' => 'string',
        'genres' => 'array',
        'actresses' => 'array',
        'gallery' => 'array',
        'favorite' => 'integer',
        'running_time' => 'integer',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'dvd_id', 'dvd_id');
    }
}
