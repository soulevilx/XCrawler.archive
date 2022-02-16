<?php

namespace App\Core\Services;

use App\Core\Models\State;
use App\Flickr\Models\FlickrProcess;

class CleanupService
{
    public function cleanup()
    {
        $this->flickrProcesses();
    }

    protected function flickrProcesses()
    {
        FlickrProcess::where(['state_code' => State::STATE_COMPLETED])->forceDelete();
    }
}
