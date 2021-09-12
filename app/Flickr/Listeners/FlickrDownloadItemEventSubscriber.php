<?php

namespace App\Flickr\Listeners;

use App\Flickr\Events\FlickrDownloadCompleted;
use App\Flickr\Events\FlickrDownloadItemCompleted;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Event;

class FlickrDownloadItemEventSubscriber
{
    public function onFlickrDownloadItemCompleted(FlickrDownloadItemCompleted $event)
    {
        $downloadItem = $event->downloadItem;
        $download = $downloadItem->download;

        if ($download->isCompleted()) {
            Event::dispatch(new FlickrDownloadCompleted($download));
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            [FlickrDownloadItemCompleted::class],
            self::class . '@onFlickrDownloadItemCompleted'
        );
    }
}
