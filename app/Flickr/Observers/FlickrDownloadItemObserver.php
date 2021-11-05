<?php

namespace App\Flickr\Observers;

use App\Core\Models\State;
use App\Flickr\Events\FlickrDownloadItemCompleted;
use App\Flickr\Jobs\FlickrDownloadItem as FlickrDownloadItemJob;
use App\Flickr\Models\FlickrDownloadItem;
use Illuminate\Support\Facades\Event;

class FlickrDownloadItemObserver
{
    public function created(FlickrDownloadItem $downloadItem)
    {
        if ($downloadItem->state_code === State::STATE_INIT) {
            FlickrDownloadItemJob::dispatch($downloadItem)->onQueue('api');
        }
    }

    public function updated(FlickrDownloadItem $downloadItem)
    {
        if ($downloadItem->isDirty('state_code') && $downloadItem->state_code === State::STATE_COMPLETED) {
            Event::dispatch(new FlickrDownloadItemCompleted($downloadItem));
        }
    }
}
