<?php

namespace App\Flickr\Models;

use App\Core\Models\BaseModel;
use App\Core\Models\Traits\HasFactory;
use App\Core\Models\Traits\HasStates;

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
}
