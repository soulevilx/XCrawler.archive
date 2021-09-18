<?php

namespace App\Flickr\Events;

use App\Flickr\Models\FlickrDownload;

class FlickrDownloadCompleted
{
    public function __construct(public FlickrDownload $download)
    {
    }
}
