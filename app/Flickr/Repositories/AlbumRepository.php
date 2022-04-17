<?php

namespace App\Flickr\Repositories;

use App\Core\Repositories\Traits\HasDefaultRepository;
use App\Flickr\Models\FlickrAlbum;
use Illuminate\Database\Eloquent\Model;

class AlbumRepository
{
    use HasDefaultRepository;

    public function __construct(public FlickrAlbum $model)
    {
    }

    public function create(array $attributes): Model
    {
        return $this->model->withTrashed()->firstOrCreate([
            'id' => $attributes['id'],
            'owner' => $attributes['owner'],
        ], $attributes);
    }
}
