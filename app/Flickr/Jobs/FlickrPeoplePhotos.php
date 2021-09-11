<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Jobs\Traits\HasFlickrMiddleware;
use App\Flickr\Models\FlickrContactProcess;
use App\Flickr\Services\FlickrService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FlickrPeoplePhotos implements ShouldQueue, ShouldBeUnique
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
        $this->contactProcess->setState(State::STATE_PROCESSING);
        $model = $this->contactProcess->model;
        $photos = $service->people()->getPhotos($model->nsid);
        $photos['photo']->each(function ($photo) use ($model) {
            $model->photos()->create($photo);
        });

        $this->contactProcess->setState(State::STATE_COMPLETED);
        // Create STEP_PHOTOSETS_LIST process
        $this->contactProcess->model->process()->create([
            'step' => FlickrContactProcess::STEP_PHOTOSETS_LIST,
            'state_code' => State::STATE_INIT,
        ]);
    }
}
