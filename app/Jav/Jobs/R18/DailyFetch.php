<?php

namespace App\Jav\Jobs\R18;

use App\Jav\Jobs\Traits\R18CrawlingMiddleware;
use App\Jav\Services\R18Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class DailyFetch implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use R18CrawlingMiddleware;

    public function handle(R18Service $service)
    {
        $service->daily();
    }
}
