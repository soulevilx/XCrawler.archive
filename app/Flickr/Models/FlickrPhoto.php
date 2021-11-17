<?php

namespace App\Flickr\Models;

use App\Core\Models\Traits\HasFactory;
use App\Core\Models\Traits\HasStates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property-read FlickrSizes $sizes
 */
class FlickrPhoto extends Model
{
    use HasFactory;
    use HasStates;

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
//        'ispublic',
//        'isfriend',
//        'isfamily',
        //'sizes',
        //'isprimary',
    ];

    protected $casts = [
        'id' => 'integer',
        'owner' => 'string',
        'secret' => 'string',
        'server' => 'string',
        'farm' => 'string',
        'title' => 'string',
//        'ispublic' => 'integer',
//        'isfriend' => 'integer',
//        'isfamily' => 'integer',
//        'isprimary' => 'integer',
        //'sizes' => 'array',
    ];

    public function albums(): BelongsToMany
    {
        return $this->belongsToMany(FlickrAlbum::class, 'flickr_album_photos', 'photo_id', 'album_id')->withTimestamps();
    }

    public function __get($key)
    {
        if ($key === 'sizes') {
            return $this->sizes()?->sizes;
        }

        return parent::__get($key);
    }

    public function sizes(): ?FlickrSizes
    {
        return FlickrSizes::where(['id' => $this->id])->first();
    }

    public function largestSize()
    {
        if (!$this->sizes) {
            return null;
        }

        $sizes = collect($this->sizes);
        $sizes = $sizes->sortBy(function ($size) {
            return $size['width'] + $size['height'];
        });

        return $sizes->last();
    }

    public function updateSizes(array $sizes): FlickrSizes
    {
        return FlickrSizes::updateOrCreate([
            'id' => $this->id,
            'sizes' => $sizes,
        ]);
    }
}
