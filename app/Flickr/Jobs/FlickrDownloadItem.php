<?php

namespace App\Flickr\Jobs;

use App\Core\Jobs\BaseJob;
use App\Core\Jobs\DownloadItem;
use App\Core\Models\State;
use App\Flickr\Models\FlickrDownloadItem as FlickrDownloadItemModel;

class FlickrDownloadItem extends BaseJob
{
    public function __construct(public FlickrDownloadItemModel $downloadItem)
    {
    }

    public function handle()
    {
        $this->downloadItem->setState(State::STATE_PROCESSING);
        $photo = $this->downloadItem->photo;
        $download = $this->downloadItem->download;

        $url = $photo->getLargestSizeUrl();

        DownloadItem::dispatch('/Flickr/' . $download->path, $url,  $this->downloadItem);
    }
}
