<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Jobs\Traits\HasFlickrMiddleware;
use App\Flickr\Models\FlickrContactProcess;
use App\Flickr\Services\FlickrService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FlickrPeopleInfo implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use HasFlickrMiddleware;

    public function __construct(public FlickrContactProcess $contactProcess)
    {
        $this->contactProcess->model->setState(State::STATE_PROCESSING);
        $this->contactProcess->setState(State::STATE_PROCESSING);
    }

    public function handle(FlickrService $service)
    {
        $model = $this->contactProcess->model;
        $info = $service->people()->getInfo($model->nsid);
        $model->update($info);
        $this->contactProcess->setState(State::STATE_COMPLETED);

        // Create STEP_PEOPLE_PHOTOS process
        $this->contactProcess->model->process()->create([
            'step' => FlickrContactProcess::STEP_PEOPLE_PHOTOS,
            'state_code' => State::STATE_INIT,
        ]);
    }
}
