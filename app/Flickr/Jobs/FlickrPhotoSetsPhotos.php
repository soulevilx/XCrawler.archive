<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Jobs\Traits\HasFlickrMiddleware;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrContactProcess;
use App\Flickr\Models\FlickrPhoto;
use App\Flickr\Services\FlickrService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FlickrPhotoSetsPhotos implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use HasFlickrMiddleware;

    public function __construct(public FlickrContactProcess $process)
    {

    }

    public function handle(FlickrService $service)
    {
        $this->process->setState(State::STATE_PROCESSING);
        /**
         * @var FlickrAlbum $model
         */
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
    }
}
