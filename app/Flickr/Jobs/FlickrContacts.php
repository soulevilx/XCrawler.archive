<?php

namespace App\Flickr\Jobs;

use App\Flickr\Services\FlickrService;

class FlickrContacts extends AbstractLimitJob
{
    public function handle(FlickrService $service)
    {
        $service->contacts()->getListAll()->each(function ($contact) use ($service) {
            $service->contacts()->create($contact);
        });
    }
}
