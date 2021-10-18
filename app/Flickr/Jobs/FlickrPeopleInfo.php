<?php

namespace App\Flickr\Jobs;

/**
 * First step in whole process
 */
class FlickrPeopleInfo extends AbstractProcessJob
{
    public function process(): bool
    {
        if (!$info = $this->service->people()->getInfo($this->process->model->nsid)) {
            return false;
        }

        $info['description'] = strip_tags($info['description']);
        return $this->process->model->update($info);
    }
}
