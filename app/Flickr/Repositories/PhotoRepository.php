<?php

namespace App\Flickr\Repositories;

use App\Core\Repositories\Traits\HasDefaultRepository;
use App\Flickr\Models\FlickrPhoto;
use Illuminate\Support\Collection;

class PhotoRepository
{
    use HasDefaultRepository;

    public function __construct(protected FlickrPhoto $model)
    {
    }

    public function getUnsizedItems(int $limit = 5): Collection
    {
        return $this->model->whereNull('sizes')
            ->limit($limit)
            ->get();
    }
}
