<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
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

    public function handle(FlickrService $service)
    {
        $service->contacts()->getAll()->each(function ($contact) {
            FlickrContact::firstOrCreate([
                'nsid' => $contact['nsid'],
            ], $contact + ['state_code' => State::STATE_INIT]);
        });
    }
}
