<?php

namespace App\Flickr\Models;

use App\Core\Models\BaseModel;
use App\Core\Models\Traits\HasFactory;
use App\Core\Models\Traits\HasStates;

/**
 * @property-read FlickrContact|FlickrAlbum $model
 */
class FlickrProcess extends BaseModel
{
    use HasFactory;
    use HasStates;

    protected $table = 'flickr_contact_processes';

    public const STEP_PEOPLE_INFO = 'people_info';
    public const STEP_PEOPLE_PHOTOS = 'people_photos';
    public const STEP_PEOPLE_FAVORITE_PHOTOS = 'people_favorite_photos';
    public const STEP_PHOTOSETS_LIST = 'photosets_list';
    public const STEP_PHOTOSETS_PHOTOS = 'photosets_photos';

    protected $fillable = [
        'step',
    ];

    public function model()
    {
        return $this->morphTo();
    }
}
