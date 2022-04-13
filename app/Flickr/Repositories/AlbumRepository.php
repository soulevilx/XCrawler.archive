<?php

namespace App\Flickr\Repositories;

use App\Core\Repositories\AbstractRepository;
use App\Flickr\Models\FlickrAlbum;

class AlbumRepository extends AbstractRepository
{
    public function __construct(public FlickrAlbum $model)
    {
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }
}
