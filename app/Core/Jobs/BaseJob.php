<?php

namespace App\Core\Jobs;

use App\Core\Jobs\Middlewares\XCrawlerMiddleware;
use App\Core\Services\Facades\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BaseJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public bool $deleteWhenMissingModels = true;

    protected string $serviceName = 'core';

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        if ('testing' === config('app.env')) {
            return [];
        }

        return [new XCrawlerMiddleware($this::class, $this->serviceName)];
    }

    public function retryUntil()
    {
        return now()->addMinutes(
            Application::getInt($this->serviceName, 'middleware.retryUntil', 60)
        );
    }
}
