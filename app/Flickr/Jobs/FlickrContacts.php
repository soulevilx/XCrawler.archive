<?php

namespace App\Flickr\Jobs;

use App\Core\Jobs\BaseJob;
use App\Flickr\Services\FlickrService;

class FlickrContacts extends BaseJob
{
    public function handle(FlickrService $service)
    {
        $service->contacts()->getListAll()->each(function ($contact) use ($service) {
            $service->contacts()->create($contact);
        });
    }
}
