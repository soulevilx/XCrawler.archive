<?php

namespace App\Flickr\Observers;

use App\Core\Models\State;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrContactProcess;

class FlickrContactObserver
{
    public function created(FlickrContact $contact)
    {
        if (!$contact->process()->doesntExist()) {
            return;
        }

        $contact->process()->create([
            'step' => FlickrContactProcess::STEP_PEOPLE_INFO,
            'state_code' => State::STATE_INIT,
        ]);
    }
}
