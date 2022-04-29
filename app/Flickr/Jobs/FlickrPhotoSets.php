<?php

namespace App\Flickr\Jobs;

class FlickrPhotoSets extends AbstractProcessJob
{
    public function process(): bool
    {
        $this->service->photosets()->getListAll($this->process->model->nsid)->each(function ($photoset) {
            $this->service->photosets()->create($photoset);
        });

        return true;
    }
}
