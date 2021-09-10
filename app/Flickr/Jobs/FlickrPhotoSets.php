<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Jobs\Traits\HasFlickrMiddleware;
use App\Flickr\Models\FlickrAlbum;
use App\Flickr\Models\FlickrContactProcess;
use App\Flickr\Services\FlickrService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FlickrPhotoSets implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use HasFlickrMiddleware;

    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 60;

    public function __construct(public FlickrContactProcess $contactProcess)
    {
        $this->contactProcess->setState(State::STATE_PROCESSING);
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->contactProcess->model->is;
    }

    public function handle(FlickrService $service)
    {
        $model = $this->contactProcess->model;
        $photosets = $service->photosets()->getAllPhotosets($model->nsid);
        $photosets->each(function ($photoset) {
            $album = FlickrAlbum::updateOrCreate([
                'id' => $photoset['id'],
                'owner' => $photoset['owner'],

            ], $photoset + ['state_code' => State::STATE_INIT]);
            // Create STEP_PHOTOSETS_PHOTOS process
            $album->process()->create([
                'step' => FlickrContactProcess::STEP_PHOTOSETS_PHOTOS,
                'state_code' => State::STATE_INIT,
            ]);
        });

        $this->contactProcess->setState(State::STATE_COMPLETED);
    }
}
