<?php

namespace App\Flickr\Models;

use App\Core\Models\BaseMongo;

class FlickrSizes extends BaseMongo
{
    protected $collection = 'flickr_sizes';

    protected $fillable = [
        'id',
        'sizes',
    ];

    protected $casts = [
        'id' => 'integer',
        'sizes' => 'array',
    ];
}
