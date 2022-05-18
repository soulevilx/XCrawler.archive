<?php

namespace App\Jav\Jobs\SCute;

use App\Core\Jobs\BaseJob;
use App\Jav\Models\SCute;
use App\Jav\Services\SCuteService;

class ItemFetch extends BaseJob
{
    protected string $service = SCuteService::SERVICE_NAME;

    public function __construct(public SCute $model)
    {
    }

    public function handle(SCuteService $service)
    {
        $service->item($this->model);
    }
}
