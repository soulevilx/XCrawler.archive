<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Models\FlickrContact;
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

    public function __construct(public FlickrContactProcess $contactProcess)
    {
        $this->contactProcess->model->setState(State::STATE_PROCESSING);
    }

    public function handle(FlickrService $service)
    {
        $model = $this->contactProcess->model;
        $info = $service->people()->getInfo($model->nsid);

        if (!$info) {
            return;
        }

        $model->update($info);
        $this->contactProcess->setState(State::STATE_COMPLETED);
    }
}
