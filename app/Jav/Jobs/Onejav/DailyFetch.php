<?php

namespace App\Jav\Jobs\Onejav;

use App\Core\Jobs\BaseJob;
use App\Jav\Services\OnejavService;

class DailyFetch extends BaseJob
{
    protected string $serviceName = OnejavService::SERVICE_NAME;

    public function handle(OnejavService $service)
    {
        $service->daily();
    }
}
