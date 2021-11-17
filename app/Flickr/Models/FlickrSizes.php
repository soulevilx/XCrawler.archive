<?php

namespace App\Flickr\Models;

use Illuminate\Database\Eloquent\Model;

class FlickrSizes extends Model
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
