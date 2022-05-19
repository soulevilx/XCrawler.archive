<?php

namespace App\Jav\Jobs\R18;

use App\Core\Jobs\BaseJob;
use App\Jav\Services\R18Service;

class ReleaseFetch extends BaseJob
{
    protected string $serviceName = R18Service::SERVICE_NAME;

    public function __construct(public string $url, public string $type)
    {
    }

    public function handle(R18Service $service)
    {
        $service->release($this->url, $this->type);
    }
}
