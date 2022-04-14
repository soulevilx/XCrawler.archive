<?php

namespace App\Jav\Jobs\XCity;

use App\Core\Models\State;
use App\Jav\Models\XCityVideo;
use App\Jav\Services\XCityVideoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VideoItemFetch implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public XCityVideo $model)
    {
        $model->setState(State::STATE_PROCESSING);
    }

    public function handle(XCityVideoService $service)
    {
        $this->model = $service->item($this->model);
        $this->model->setState(State::STATE_COMPLETED);
    }

    public function failed()
    {
        $this->model->setState(State::STATE_INIT);
    }
}
