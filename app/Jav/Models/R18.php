<?php

namespace App\Jav\Models;

use App\Core\Models\Traits\HasFactory;
use App\Core\Models\Traits\HasStates;
use App\Jav\Models\Interfaces\MovieInterface;
use App\Jav\Models\Traits\HasCover;
use App\Jav\Models\Traits\HasMovie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $url
 * @property string $cover
 * @property string $title
 * @property string $release_date
 * @property int $runtime
 * @property string $director
 * @property string $studio
 * @property string $label
 * @property string $channel
 * @property string $content_id
 * @property string $dvd_id
 * @property string $series
 * @property string $languages
 * @property string $sample
 * @property array $gallery
 */
class R18 extends Model implements MovieInterface
{
    use HasFactory;
    use SoftDeletes;
    use HasMovie;
    use HasStates;
    use HasCover;

    public const MOVIE_DETAIL_ENDPOINT = '/api/v4f/contents';

    protected $table = 'r18';

    public const MOVIE_URLS = [
        'release' => '/videos/vod/movies/list/pagesize=30/price=all/sort=new/type=all',
        'prime' => '/videos/channels/prime',
        'playgirl' => '/videos/channels/playgirl',
        'avstation' => '/videos/channels/avstation',
        'dream' => '/videos/channels/dream',
        's1' => '/videos/channels/s1/',
        'moodyz' => '/videos/channels/moodyz',
        'sod' => '/videos/channels/sod',
    ];

    public const MOVIE_LIST_URL = self::MOVIE_URLS['release'];

    protected $fillable = [
        'url',
        'cover',
        'title',
        'release_date',
        'runtime',
        'director',
        'studio',
        'maker',
        'label',
        'channels',
        'content_id',
        'dvd_id',
        'series',
        'languages',
        'sample',
        'images',
        'gallery',
        'genres',
        'performers',
    ];

    protected $casts = [
        'url' => 'string',
        'cover' => 'string',
        'title' => 'string',
        'dvd_id' => 'string',
        'content_id' => 'string',
        'maker' => 'string',
        'label' => 'string',
        'channels' => 'array',
        'release_date' => 'datetime:Y-m-d',
        'genres' => 'array',
        'performers' => 'array',
        'sample' => 'array',
        'images' => 'array',
        'gallery' => 'array',
        'series' => 'array',
        'updated_at' => 'datetime:Y-m-d H:m:s',
        'created_at' => 'datetime:Y-m-d H:m:s',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class, 'content_id', 'content_id');
    }

    public function getName(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $version
     * @return string|null
     */
    public function sample(string $version = 'high'): ?string
    {
        if (!$this->sample) {
            return null;
        }

        if (count($this->sample) > 1) {
            return $this->sample[$version] ?? null;
        }

        return $this->sample[0] ?? null;
    }
}
