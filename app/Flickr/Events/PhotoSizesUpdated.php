<?php

namespace App\Flickr\Events;

use App\Flickr\Models\FlickrPhoto;

class PhotoSizesUpdated
{
    public function __construct(public FlickrPhoto $model)
    {
    }
}
