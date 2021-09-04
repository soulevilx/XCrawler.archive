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
 * @property string $url
 * @property string $torrent
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

    public function download()
    {
        $this->refetch();
        $file = fopen(config('services.jav.download_dir') . '/' . basename($this->torrent), 'wb');
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_FILE, $file);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, Onejav::BASE_URL . $this->torrent);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_exec($ch);
        curl_close($ch);
        fclose($file);
    }
}
