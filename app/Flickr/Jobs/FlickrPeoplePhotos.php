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

        $photos->each(function ($photo) {
            $this->process->model->photos()->firstOrCreate([
                'id' => $photo['id'],
                'owner' => $photo['owner'],
            ], $photo);
        });

        return true;
    }
}
