<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Models\FlickrContact;

class FlickrFavorites extends AbstractProcessJob
{
    public function process(): bool
    {
        $photos = $this->service->favorites()->getListAll($this->process->model->nsid);

        if (empty($photos)) {
            return true;
        }

        // Create new photos
        $photos->each(function ($photo) {
            if (!$contact = FlickrContact::findByNsid($photo['owner'])) {
                $contact = FlickrContact::withTrashed()->firstOrCreate([
                    'nsid' => $photo['owner'],
                ], [
                    'state_code' => State::STATE_INIT,
                ]);
            }

            if (!$contact->trashed()) {
                $contact->photos()->firstOrCreate([
                    'id' => $photo['id'],
                    'owner' => $photo['owner'],
                ], $photo);
            }
        });

        return true;
    }
}
