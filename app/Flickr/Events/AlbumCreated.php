<?php

namespace App\Flickr\Events;

use App\Flickr\Models\FlickrAlbum;

class AlbumCreated
{
    public function __construct(public FlickrAlbum $model)
    {
    }
}
