<?php

namespace App\Jav\Jobs\R18;

use App\Core\Jobs\BaseJob;
use App\Jav\Services\R18Service;

class DailyFetch extends BaseJob
{
    protected string $service = R18Service::SERVICE_NAME;

    public function __construct(public string $url)
    {
    }

    public function handle(R18Service $service)
    {
        $service->daily($this->url);
    }
}
