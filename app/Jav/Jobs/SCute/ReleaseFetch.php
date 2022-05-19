<?php

namespace App\Jav\Jobs\SCute;

use App\Core\Jobs\BaseJob;
use App\Jav\Services\SCuteService;

class ReleaseFetch extends BaseJob
{
    protected string $serviceName = SCuteService::SERVICE_NAME;

    public function handle(SCuteService $service)
    {
        $service->release();
    }
}
