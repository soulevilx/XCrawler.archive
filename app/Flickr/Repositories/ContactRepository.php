<?php

namespace App\Flickr\Repositories;

use App\Core\Repositories\Traits\HasDefaultRepository;
use App\Flickr\Models\FlickrContact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ContactRepository
{
    use HasDefaultRepository;

    public function __construct(protected FlickrContact $model)
    {
    }

    public function findByNsid(string $nsid)
    {
        return $this->getModel()->withTrashed()->where(['nsid' => $nsid])->first();
    }

    public function create(array $attributes): Model
    {
        return $this->model->withTrashed()->firstOrCreate([
            'nsid' => $attributes['nsid'],
        ], $attributes);
    }

    public function addPhotos(Collection $photos)
    {
        foreach ($photos as $photo) {
            $this->model->photos()->firstOrCreate([
                'id' => $photo['id'],
                'owner' => $photo['owner'],
            ], $photo);
        }
    }
}
