<?php

namespace App\Flickr\Events;

use App\Flickr\Models\FlickrDownloadItem;

class FlickrDownloadItemCompleted
{
    public function __construct(public FlickrDownloadItem $downloadItem)
    {
    }
}
