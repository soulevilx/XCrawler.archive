<?php

namespace App\Flickr\Repositories;

use App\Core\Repositories\Traits\HasDefaultRepository;
use App\Flickr\Models\FlickrAlbum;

class AlbumRepository
{
    use HasDefaultRepository;

    public function __construct(public FlickrAlbum $model)
    {
    }
}
