<?php

namespace App\Flickr\Repositories;

use App\Core\Repositories\Traits\HasDefaultRepository;
use App\Flickr\Models\FlickrPhoto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class PhotoRepository
{
    use HasDefaultRepository;

    public function __construct(protected FlickrPhoto $model)
    {
    }

    public function create(array $attributes): Model
    {
        return $this->model->withTrashed()->firstOrCreate([
            'id' => $attributes['id'],
            'owner' => $attributes['owner'],
        ], $attributes);
    }

    public function getUnsizedItems(int $limit = 5): Collection
    {
        return $this->model->whereNull('sizes')
            ->limit($limit)
            ->get();
    }
}
