<?php

namespace App\Flickr\Jobs;

use App\Flickr\Models\FlickrPhoto;
use App\Flickr\Services\FlickrService;

class FlickrPhotoSetsPhotos extends BaseProcessJob
{
    public function process(): bool
    {
        $service = app(FlickrService::class);
        $model = $this->process->model;
        $photos = $service->photosets()->getAllPhotos($model->id, $model->owner);
        $photos->each(function ($photo) use ($model) {
            unset($photo['isprimary']);
            unset($photo['ispublic']);
            unset($photo['isfriend']);
            unset($photo['isfamily']);
            $photo = FlickrPhoto::firstOrCreate([
                'id' => $photo['id'],
                'owner' => $model->owner,
            ], $photo);


            $model->photos()->syncWithoutDetaching([$photo->id]);
        });

        return true;
    }
}
