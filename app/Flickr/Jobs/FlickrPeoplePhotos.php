<?php

namespace App\Flickr\Jobs;

class FlickrPeoplePhotos extends AbstractProcessJob
{
    public function process(): bool
    {
        $photos = $this->service->people()->getPhotos($this->process->model->nsid);

        if (empty($photos)) {
            return true;
        }

        // Create new photos
        $photos['photo']->each(function ($photo) {
            $this->process->model->photos()->firstOrCreate([
                'id' => $photo['id'],
                'owner' => $photo['owner'],
            ], $photo);
        });

        return true;
    }
}
