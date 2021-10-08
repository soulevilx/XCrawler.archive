<?php

namespace App\Flickr\Jobs;

use App\Flickr\Services\FlickrService;

class FlickrPeoplePhotos extends BaseProcessJob
{
    public function process(): bool
    {
        $service = app(FlickrService::class);
        $model = $this->process->model;
        $photos = $service->people()->getPhotos($model->nsid);

        if (empty($photos)) {
            return true;
        }

        // Create new photos
        $photos['photo']->each(function ($photo) use ($model) {
            $model->photos()->firstOrCreate([
                'id' => $photo['id'],
                'owner' => $photo['owner'],
            ], $photo);
        });

        return true;
    }
}
