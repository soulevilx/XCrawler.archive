<?php

namespace App\Flickr\Observers;

use App\Core\Models\State;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrContactProcess;

class FlickrAlbumObserver
{
    public function created(FlickrAlbum $album)
    {
        if (!$album->process()->doesntExist()) {
            return;
        }

        // Create STEP_PHOTOSETS_PHOTOS process
        $album->process()->create([
            'step' => FlickrContactProcess::STEP_PHOTOSETS_PHOTOS,
            'state_code' => State::STATE_INIT,
        ]);
    }
}
