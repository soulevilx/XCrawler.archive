<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Services\FlickrService;

class FlickrPhotoSets extends BaseProcessJob
{
    public function process(): bool
    {
        $service = app(FlickrService::class);

        if (!$model = $this->process->model) {
            return false;
        }

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
