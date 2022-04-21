<?php

namespace App\Jav\Jobs\XCity;

use App\Jav\Models\State;
use App\Jav\Models\XCityIdol;
use App\Jav\Services\XCityIdolService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IdolItemFetch implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

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
