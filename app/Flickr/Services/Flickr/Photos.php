<?php

namespace App\Flickr\Services\Flickr;

use App\Flickr\Events\FlickrPhotoCreated;
use App\Flickr\Events\PhotoSizesUpdated;
use App\Flickr\Models\FlickrPhoto;
use Illuminate\Support\Facades\Event;

class Photos extends BaseFlickr
{
    public function getSizes(int $photo_id): array
    {
        $response = $this->call(func_get_args(), __FUNCTION__);
        $response['sizes']['size'] = collect($response['sizes']['size']);

        return $response['sizes'];
    }

    public function create(array $attributes): FlickrPhoto
    {
        $model = FlickrPhoto::firstOrCreate([
            'id' => $attributes['id'],
            'owner' => $attributes['owner'],
        ], $attributes);

        if ($model->wasRecentlyCreated) {
            Event::dispatch(new FlickrPhotoCreated($model));
        }

        return $model;
    }

    public function updateSizes(FlickrPhoto $model)
    {
        $sizes = $this->getSizes($model->id);
        $model->update([
            'sizes' => $sizes['size']->toArray(),
        ]);

        Event::dispatch(new PhotoSizesUpdated($model));
    }
}
