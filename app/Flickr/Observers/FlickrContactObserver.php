<?php

namespace App\Flickr\Observers;

use App\Flickr\Events\FlickrContactCreated;
use App\Flickr\Models\FlickrContact;
use Illuminate\Support\Facades\Event;

class FlickrContactObserver
{
    public function created(FlickrContact $contact)
    {
        Event::dispatch(new FlickrContactCreated($contact));
    }
}
