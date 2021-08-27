<?php

namespace App\Jav\Jobs\R18;

use App\Jav\Jobs\Traits\HasCrawlingMiddleware;
use App\Jav\Services\R18Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class ReleaseFetch implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use HasCrawlingMiddleware;

    protected int $allow = 1;
    protected int $releaseAfterMinutes = 5;

    public function handle(R18Service $service)
    {
        $service->release();
    }
}
