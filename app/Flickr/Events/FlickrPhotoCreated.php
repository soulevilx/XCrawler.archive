<?php

namespace App\Flickr\Events;

use App\Flickr\Models\FlickrPhoto;

class FlickrPhotoCreated
{
    public function __construct(public FlickrPhoto $model)
    {
    }
}
