<?php

namespace App\Jav\Jobs\XCity\Idol;

use App\Core\Jobs\BaseJob;
use App\Jav\Models\State;
use App\Jav\Models\XCityIdol;
use App\Jav\Services\XCityIdolService;

class FetchIdol extends BaseJob
{
    protected string $service = 'xcity';

    public function __construct(public XCityIdol $model)
    {
        $model->setState(State::STATE_PROCESSING);
    }

    public function handle(XCityIdolService $service)
    {
        $this->model = $service->item($this->model);
        $this->model->setState(State::STATE_COMPLETED);
    }

    public function failed()
    {
        $this->model->setState(State::STATE_INIT);
    }
}
