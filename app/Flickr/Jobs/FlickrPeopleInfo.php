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
        $model = $this->process->model;
        $info = $service->people()->getInfo($model->nsid);
        $model->update($info);

        return true;
    }
}
