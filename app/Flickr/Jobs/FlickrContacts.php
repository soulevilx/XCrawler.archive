<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Jobs\Traits\HasFlickrMiddleware;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Services\FlickrService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class FlickrContacts implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use HasFlickrMiddleware;

    public function handle(FlickrService $service)
    {
        $service->contacts()->getListAll()->each(function ($contact) {
            $nsid = $contact['nsid'];
            unset($contact['nsid']);

            if (!FlickrContact::findByNsid($nsid)) {
                FlickrContact::withTrashed()->firstOrCreate([
                    'nsid' => $nsid,
                ], $contact + ['state_code' => State::STATE_INIT]);
            }
        });
    }
}
