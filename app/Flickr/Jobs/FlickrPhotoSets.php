<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;

class FlickrPhotoSets extends AbstractProcessJob
{
    public function process(): bool
    {
        $photosets = $this->service->photosets()->getListAll($this->process->model->nsid);
        $photosets->each(function ($photoset) {
            $this->process->model->albums()->firstOrCreate(
                [
                    'id' => $photoset['id'],
                    'owner' => $photoset['owner'],
                ],
                $photoset + ['state_code' => State::STATE_INIT]
            );
        });

        return true;
    }
}
