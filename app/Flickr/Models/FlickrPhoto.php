<?php

namespace App\Flickr\Models;

use App\Core\Models\Traits\HasFactory;
use App\Flickr\Services\FlickrService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property array $sizes
 */
class FlickrPhoto extends Model
{
    use HasFactory;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $fillable = [
        'id',
        'owner',
        'secret',
        'server',
        'farm',
        'title',
        'ispublic',
        'isfriend',
        'isfamily',
        'sizes',
        'isprimary',
    ];

    protected $casts = [
        'id' => 'integer',
        'owner' => 'string',
        'secret' => 'string',
        'server' => 'string',
        'farm' => 'string',
        'title' => 'string',
        'ispublic' => 'integer',
        'isfriend' => 'integer',
        'isfamily' => 'integer',
        'isprimary' => 'integer',
        'sizes' => 'array',
    ];

    public function albums(): BelongsToMany
    {
        return $this->belongsToMany(FlickrAlbum::class, 'flickr_album_photos', 'photo_id', 'album_id')->withTimestamps();
    }

    public function largestSize()
    {
        if (!$this->sizes) {
            return null;
        }

        return collect($this->sizes)->sortBy(function ($size) {
            return $size['width'] + $size['height'];
        })->last();
    }

    /**
     * @return string
     */
    public function getLargestSizeUrl(): string
    {
        if (!$size = $this->largestSize()) {
            $service = app(FlickrService::class);
            $sizes = $service->photos()->getSizes($this->id);
            $this->update([
                'sizes' => $sizes['size']->toArray(),
            ]);

            $size = $this->largestSize();
        }

        return $size['source'];
    }
}
