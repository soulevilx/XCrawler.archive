<?php

namespace App\Flickr\Models;

use App\Core\Models\State;
use App\Core\Models\Traits\HasFactory;
use App\Core\Models\Traits\HasStates;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $path
 * @property integer $total
 * @property-read FlickrAlbum|FlickrContact $model
 */
class FlickrDownload extends Model
{
    use HasFactory;
    use HasStates;

    protected $fillable = [
        'name',
        'path',
        'total',
        'model_id',
        'model_type',
    ];

    protected $casts = [
        'name' => 'string',
        'path' => 'string',
        'total' => 'integer',
        'model_id' => 'string',
        'model_type' => 'string',
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function items()
    {
        return $this->hasMany(FlickrDownloadItem::class, 'download_id');
    }

    public function isCompleted(): bool
    {
        return $this->total === $this->items()->where(['state_code' => State::STATE_COMPLETED])->count();
    }
}
