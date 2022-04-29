<?php

namespace App\Flickr\Jobs;

class FlickrPeoplePhotos extends AbstractProcessJob
{
    public function process(): bool
    {
        $photos = $this->service->people()->getPhotosAll($this->process->model->nsid);

        if ($photos->isEmpty()) {
            return true;
        }

        $this->service->people()->addPhotos($this->process->model, $photos);

        return true;
    }
}
