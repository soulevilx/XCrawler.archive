<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Services\FlickrService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FlickrPeopleInfo implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public FlickrContact $contact)
    {
    }

    public function handle(FlickrService $service)
    {
        $info = $service->people()->getInfo($this->contact->nsid);

        if (!$info) {
            return;
        }

        $this->contact->update($info);
        $this->contact->setState(State::STATE_COMPLETED);
    }
}
