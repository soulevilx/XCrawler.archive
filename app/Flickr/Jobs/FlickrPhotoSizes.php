<?php

namespace App\Flickr\Jobs;

use App\Flickr\Models\FlickrPhoto;
use App\Flickr\Services\FlickrService;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class FlickrPhotoSizes extends AbstractLimitJob implements ShouldBeUnique
{
    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 60;

    public function __construct(public FlickrPhoto $photo)
    {
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->photo->id;
    }

    public function handle(FlickrService $service)
    {
        $service->photos()->updateSizes($this->photo);
    }
}
