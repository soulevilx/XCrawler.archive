<?php

namespace App\Flickr\Jobs;

use App\Core\Models\State;
use App\Flickr\Models\FlickrContactProcess;
use App\Flickr\Services\FlickrService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FlickrPeoplePhotos implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public FlickrContactProcess $contactProcess)
    {
        $this->contactProcess->setState(State::STATE_PROCESSING);
        $this->contactProcess->update(['step' => FlickrContactProcess::STEP_PEOPLE_PHOTOS]);
    }

    public function handle(FlickrService $service)
    {
        $model = $this->contactProcess->model;

        if (!$photos = $service->people()->getPhotos($model->nsid)) {
            return;
        }

        $photos['photo']->each(function ($photo) use ($model) {
            $photo = $model->photos()->create($photo);
        });

        $this->contactProcess->setState(State::STATE_COMPLETED);
    }
}
