<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Services\FlickrService;

class FlickrFavorites extends BaseProcessJob
{
    public function process(): bool
    {
        $service = app(FlickrService::class);
        $model = $this->process->model;
        $photos = $service->favorites()->getListAll($model->nsid);

        if (empty($photos)) {
            return true;
        }

        // Create new photos
        $photos->each(function ($photo) {
            $contact = FlickrContact::firstOrCreate([
                'nsid' => $photo['owner'],
            ], [
                'state_code' => State::STATE_INIT,
            ]);

            $contact->photos()->firstOrCreate([
                'id' => $photo['id'],
                'owner' => $photo['owner'],
            ], $photo);
        });

        return true;
    }
}
