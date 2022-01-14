<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Services\FlickrService;

class FlickrContacts extends AbstractLimitJob
{
    public function handle(FlickrService $service)
    {
        $service->contacts()->getListAll()->each(function ($contact) {
            if (!FlickrContact::findByNsid($contact['nsid'])) {
                FlickrContact::withTrashed()->firstOrCreate([
                    'nsid' => $contact['nsid'],
                ], $contact + ['state_code' => State::STATE_INIT]);
            }
        });
    }
}
