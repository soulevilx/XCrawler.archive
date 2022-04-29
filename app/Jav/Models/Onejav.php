<?php

namespace App\Jav\Models;

use App\Core\Models\Download;
use App\Core\Models\Traits\HasFactory;
use App\Jav\Models\Interfaces\MovieInterface;
use App\Jav\Models\Traits\HasMovie;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * @property string $url
 * @property string $cover
 * @property array $gallery
 * @property float $size
 * @property Carbon $date
 * @property string $torrent
 * @property-read Download $downloads
 */
class Onejav extends Model implements MovieInterface
{
    use HasFactory;
    use SoftDeletes;
    use HasMovie;
    use Notifiable;

    protected $table = 'onejav';

    protected $fillable = [
        'url',
        'cover',
        'gallery',
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
        'gallery' => 'array',
        'size' => 'float',
        'date' => 'datetime:Y-m-d',
        'genres' => 'array',
        'performers' => 'array',
        'description' => 'string',
        'torrent' => 'string',
    ];

    /**
     * Onejav have no state.
     *
     * @return bool
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
