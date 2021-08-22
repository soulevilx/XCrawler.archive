<?php

namespace App\Jav\Models;

use App\Core\Models\Traits\HasFactory;
use App\Jav\Crawlers\OnejavCrawler;
use App\Jav\Models\Interfaces\MovieInterface;
use App\Jav\Models\Traits\HasDefaultMovie;
use App\Jav\Models\Traits\HasMovieObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string url
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

    protected $dates = [
        'date',
    ];

    public function isDownloadable(): bool
    {
        return true;
    }

    /**
     * Onejav have no state.
     */
    public function isCompletedState(): bool
    {
        return true;
    }

    public function refetch(): self
    {
        $crawler = app(OnejavCrawler::class);
        $item = $crawler->getItems($this->url)->first();
        $this->update($item->getArrayCopy());

        return $this->refresh();
    }
}
