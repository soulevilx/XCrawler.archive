<?php

namespace App\Flickr\Repositories;

use App\Core\Repositories\AbstractRepository;
use App\Flickr\Models\FlickrContact;

class ContactRepository extends AbstractRepository
{
    public function __construct(protected FlickrContact $model)
    {
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    public function findByNsid(string $nsid)
    {
        return $this->getModel()->withTrashed()->where(['nsid' => $nsid])->first();
    }
}
