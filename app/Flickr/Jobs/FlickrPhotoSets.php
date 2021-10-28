<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Models\FlickrAlbum;

class FlickrPhotoSets extends AbstractProcessJob
{
    public function process(): bool
    {
        $photosets = $this->service->photosets()->getListAll($this->process->model->nsid);
        $photosets->each(function ($photoset) {
            if (!FlickrAlbum::withTrashed()->where(['id' => $photoset['id'], 'owner' => $photoset['id']])->exists()) {
                $this->process->model->albums()->firstOrCreate(
                    [
                        'id' => $photoset['id'],
                        'owner' => $photoset['owner'],
                    ],
                    $photoset + ['state_code' => State::STATE_INIT]
                );
            }
        });

        return true;
    }
}
