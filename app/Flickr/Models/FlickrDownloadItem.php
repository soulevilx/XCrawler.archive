<?php

namespace App\Flickr\Models;

use App\Core\Models\BaseModel;
use App\Core\Models\Traits\HasFactory;
use App\Core\Models\Traits\HasStates;
use Illuminate\Database\Eloquent\Model;

/**
 * @property  integer $id
 * @property-read  FlickrPhoto $photo
 * @property-read  FlickrDownload $download
 * @package App\Models
 */
class FlickrDownloadItem extends Model
{
    use HasFactory;
    use HasStates;

    protected $fillable = [
        'download_id',
        'photo_id',
    ];

    public function download()
    {
        return $this->belongsTo(FlickrDownload::class, 'download_id');
    }

    public function photo()
    {
        return $this->belongsTo(FlickrPhoto::class, 'photo_id');
    }
}
