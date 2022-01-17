<?php

namespace App\Flickr\Jobs;

use App\Flickr\Models\FlickrPhoto;

class FlickrPhotoSetsPhotos extends AbstractProcessJob
{
    public function process(): bool
    {
        $this->service->photosets()->getAllPhotos($this->process->model->id, $this->process->model->owner)->each(function ($photo) {
            unset($photo['isprimary']);
            unset($photo['ispublic']);
            unset($photo['isfriend']);
            unset($photo['isfamily']);
            $photo = FlickrPhoto::firstOrCreate([
                'id' => $photo['id'],
                'owner' => $this->process->model->owner,
            ], $photo);

            $this->process->model->photos()->syncWithoutDetaching([$photo->id]);
        });

        return true;
    }
}
