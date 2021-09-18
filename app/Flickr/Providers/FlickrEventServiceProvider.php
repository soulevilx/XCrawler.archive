<?php

namespace App\Flickr\Providers;

use App\Flickr\Listeners\FlickrContactEventSubscriber;
use App\Flickr\Listeners\FlickrDownloadItemEventSubscriber;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrDownloadItem;
use App\Flickr\Observers\FlickrAlbumObserver;
use App\Flickr\Observers\FlickrContactObserver;
use App\Flickr\Observers\FlickrDownloadItemObserver;
use App\Providers\EventServiceProvider;

class FlickrEventServiceProvider extends EventServiceProvider
{
    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        FlickrContactEventSubscriber::class,
        FlickrDownloadItemEventSubscriber::class
    ];

    public function boot()
    {
        parent::boot();

        FlickrContact::observe(FlickrContactObserver::class);
        FlickrAlbum::observe(FlickrAlbumObserver::class);
        FlickrDownloadItem::observe(FlickrDownloadItemObserver::class);
    }
}
