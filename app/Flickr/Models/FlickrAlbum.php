<?php

namespace App\Flickr\Models;

use App\Core\Models\BaseModel;
use App\Core\Models\Traits\HasFactory;
use App\Core\Models\Traits\HasStates;

class FlickrAlbum extends BaseModel
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
        'primary',
        'secret',
        'server',
        'farm',
        'photos',
        'title',
        'description',
    ];

    protected $casts = [
        'owner' => 'string',
        'title' => 'string',
        'description' => 'string',
    ];

    public function photos()
    {
        return $this->belongsToMany(FlickrPhoto::class, 'flickr_album_photos', 'album_id', 'photo_id')->withTimestamps();
    }

    public function owner()
    {
        return $this->belongsTo(FlickrContact::class, 'owner', 'nsid');
    }

    public function process()
    {
        return $this->morphMany(FlickrContactProcess::class, 'model');
    }
}
