<?php

namespace App\Flickr\Services\Flickr;

use App\Flickr\Events\FlickrPhotoCreated;
use App\Flickr\Events\PhotoSizesUpdated;
use App\Flickr\Models\FlickrPhoto;
use App\Flickr\Repositories\PhotoRepository;
use App\Flickr\Services\Flickr\Traits\HasFlickrClient;
use App\Flickr\Services\FlickrService;
use Illuminate\Support\Facades\Event;

class Photos
{
    use HasFlickrClient;

    public function __construct(private FlickrService $service, private PhotoRepository $repository)
    {
    }

    public function getSizes(int $photo_id): array
    {
        $response = $this->call(func_get_args(), __FUNCTION__);
        $response['sizes']['size'] = collect($response['sizes']['size']);

        return $response['sizes'];
    }

    public function create(array $attributes): FlickrPhoto
    {
        $model = $this->repository->firstOrCreate([
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
