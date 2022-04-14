<?php

namespace App\Flickr\Models;

use App\Core\Models\Traits\HasFactory;
use App\Core\Models\Traits\HasStates;
use App\Flickr\Models\Traits\HasProcesses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
class FlickrAlbum extends Model
{
    use HasFactory;
    use HasStates;
    use SoftDeletes;
    use HasProcesses;

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
        return $this->belongsToMany(FlickrPhoto::class, 'flickr_album_photos', 'album_id', 'photo_id')
            ->withTrashed()
            ->withTimestamps();
    }

    public function contact()
    {
        return $this->belongsTo(FlickrContact::class, 'owner', 'nsid');
    }
}
