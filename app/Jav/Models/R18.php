<?php

namespace App\Jav\Models;

use App\Core\Models\BaseModel;
use App\Core\Models\Traits\HasFactory;
use App\Core\Models\Traits\HasStates;
use App\Jav\Crawlers\R18Crawler;
use App\Jav\Models\Interfaces\MovieInterface;
use App\Jav\Models\Traits\HasDefaultMovie;
use App\Jav\Models\Traits\HasMovieObserver;
use Carbon\Carbon;
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
class R18 extends BaseModel implements MovieInterface
{
    use HasFactory;
    use SoftDeletes;
    use HasMovieObserver;
    use HasDefaultMovie;
    use HasStates;

    public const SERVICE = 'r18';
    public const BASE_URL = 'https://www.r18.com';

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

    public function isDownloadable(): bool
    {
        return false;
    }

    public function getName(): ?string
    {
        return $this->title;
    }

    public function refetch(): self
    {
        $crawler = app(R18Crawler::class);
        $item = $crawler->getItem($this->content_id);
        // Can't get item
        if (!$item) {
            return $this;
        }
        $item['runtime'] = $item['runtime_minutes'];
        $item['release_date'] = Carbon::createFromFormat('Y-m-d H:m:s', $item['release_date']);
        $item['maker'] = $item['maker']['name'];
        $item['label'] = $item['label']['name'];

        $item['series'] = $item['series'] ? $item['series']['name'] : [];

        if (is_array($item['categories'])) {
            foreach ($item['categories'] as $genre) {
                $item['genres'][] = $genre['name'];
            }
        }

        if (is_array($item['actresses'])) {
            foreach ($item['actresses'] as $performer) {
                $item['performers'][] = $performer['name'];
            }
        }

        if (is_array($item['channels'])) {
            foreach ($item['channels'] as $channel) {
                $item['channels'][] = $channel['name'];
            }
        }

        //$item['url'] = $item['detail_url'];
        $item['cover'] = $item['cover'] ?? $item['images']['jacket_image']['large'];

        if ($item) {
            $this->update($item);
        }

        return $this->refresh();
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
