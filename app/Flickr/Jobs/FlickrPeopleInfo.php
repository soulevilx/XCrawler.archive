<?php

namespace App\Flickr\Jobs;

use App\Flickr\Services\FlickrService;

/**
 * First step in whole process
 */
class FlickrPeopleInfo extends BaseProcessJob
{
    public function process(): bool
    {
        $service = app(FlickrService::class);

        if (!$model = $this->process->model) {
            return false;
        }

        $info = $service->people()->getInfo($model->nsid);

        if (!$info) {
            return false;
        }

        return $model->update($info);
    }
}
