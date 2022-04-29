<?php

namespace App\Flickr\Events;

use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrPhoto;

class PhotoAddedToAlbum
{
    public function __construct(public FlickrAlbum $album, public FlickrPhoto $photo)
    {
    }
}
