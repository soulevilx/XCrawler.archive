<?php

namespace App\Jav\Jobs\R18;

use App\Core\Jobs\BaseJob;
use App\Jav\Models\R18;
use App\Jav\Models\State;
use App\Jav\Services\R18Service;

class ItemFetch extends BaseJob
{
    protected string $service = R18Service::SERVICE_NAME;

    public function __construct(public R18 $model)
    {
        $model->setState(State::STATE_PROCESSING);
    }

    public function handle(R18Service $service)
    {
        $this->model = $service->item($this->model);
        $this->model->update([
            'state_code' => State::STATE_COMPLETED,
        ]);
    }

    public function failed()
    {
        $this->model->setState(State::STATE_INIT);
    }
}
