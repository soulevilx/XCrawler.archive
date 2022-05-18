<?php

namespace App\Jav\Jobs\XCity;

use App\Core\Jobs\BaseJob;
use App\Jav\Models\State;
use App\Jav\Models\XCityVideo;
use App\Jav\Services\XCityVideoService;

class VideoItemFetch extends BaseJob
{
    protected string $service = 'xcity';

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
