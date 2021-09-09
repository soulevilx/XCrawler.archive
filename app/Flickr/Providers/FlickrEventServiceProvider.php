<?php

namespace App\Flickr\Providers;

use App\Flickr\Models\FlickrContact;
use App\Flickr\Observers\FlickrContactObserver;
use App\Providers\EventServiceProvider;

class FlickrEventServiceProvider  extends EventServiceProvider
{
    public function boot()
    {
        parent::boot();

        FlickrContact::observe(FlickrContactObserver::class);
    }
}
