<?php

namespace App\Jav\Jobs\Onejav;

use App\Core\Services\ApplicationService;
use App\Jav\Services\OnejavService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class ReleaseFetch implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function handle(OnejavService $service)
    {
        $service->release();
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [new WithoutOverlapping(ApplicationService::getConfig('onejav', 'current_page', 1))];
    }
}
