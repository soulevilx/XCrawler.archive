<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Services\FlickrService;

class FlickrPhotoSets extends BaseProcessJob
{
    public function process(): bool
    {
        $service = app(FlickrService::class);
        $model = $this->process->model;
        $photosets = $service->photosets()->getListAll($model->nsid);
        $photosets->each(function ($photoset) use ($model) {
            $model->albums()->firstOrCreate(
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
