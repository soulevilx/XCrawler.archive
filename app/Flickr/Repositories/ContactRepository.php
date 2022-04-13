<?php

namespace App\Flickr\Repositories;

use App\Core\Repositories\AbstractRepository;
use App\Flickr\Models\FlickrContact;

class ContactRepository extends AbstractRepository
{
    public function __construct(protected FlickrContact $model)
    {
    }

    public function findByNsid(string $nsid)
    {
        return $this->getModel()->withTrashed()->where(['nsid' => $nsid])->first();
    }
}
