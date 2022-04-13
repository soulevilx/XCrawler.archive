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
            $contact = $this->service->contacts()->create(['nsid' => $photo['owner']]);
            if (!$contact->trashed()) {
                $this->service->photos()->create($photo);
            }
        });

        return true;
    }
}
