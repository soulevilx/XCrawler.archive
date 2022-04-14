<?php

namespace App\Flickr\Jobs;

class FlickrPhotoSetsPhotos extends AbstractProcessJob
{
    public function process(): bool
    {
        $this->service->photosets()->addPhotos($this->process->model);

        return true;
    }
}
