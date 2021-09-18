<?php

namespace App\Flickr\Models;

use App\Core\Models\BaseModel;
use App\Core\Models\Traits\HasFactory;
use App\Core\Models\Traits\HasStates;

/**
 * @property string $id
 * @property string $owner
 * @property int $primary
 * @property string $secret
 * @property int $server
 * @property int $farm
 * @property int $photos
 * @property string $title
 * @property string $description
 * @property string $google_album_id
 */
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
        'id' => 'integer',
        'owner' => 'string',
        'title' => 'string',
        'description' => 'string',
    ];

    /**
     * Actually Album hasMany photos
     * but we are using belongsToMany because we need pivot table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function photos()
    {
        return $this->belongsToMany(FlickrPhoto::class, 'flickr_album_photos', 'album_id', 'photo_id')->withTimestamps();
    }

    public function contact()
    {
        return $this->belongsTo(FlickrContact::class, 'owner', 'nsid');
    }

    public function process()
    {
        return $this->morphMany(FlickrProcess::class, 'model');
    }
}
