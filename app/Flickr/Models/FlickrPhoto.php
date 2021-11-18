<?php

namespace App\Flickr\Models;

use App\Core\Models\BaseModel;
use App\Core\Models\Traits\HasFactory;
use App\Core\Models\Traits\HasStates;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FlickrPhoto extends BaseModel
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

        $sizes = collect($this->sizes);
        $sizes = $sizes->sortBy(function ($size) {
            return $size['width'] + $size['height'];
        });

        return $sizes->last();
    }
}
