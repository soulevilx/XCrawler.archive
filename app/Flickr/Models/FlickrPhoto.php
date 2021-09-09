<?php

namespace App\Flickr\Models;

use App\Core\Models\Traits\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'state_code',
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
        'state_code' => 'string',
    ];
}
