<?php

namespace App\Jav\Jobs\XCity;

use App\Core\Models\State;
use App\Jav\Jobs\Traits\HasCrawlingMiddleware;
use App\Jav\Models\XCityIdol;
use App\Jav\Services\XCityService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ItemFetch implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public XCityIdol $model)
    {
        $model->setState(State::STATE_PROCESSING);
    }

    public function handle(XCityService $service)
    {
        $this->model = $service->item($this->model);
        $this->model->setState(State::STATE_COMPLETED);
    }
}
