<?php

namespace App\Jav\Jobs\Traits;

use App\Core\Services\Facades\Application;
use Spatie\RateLimitedMiddleware\RateLimited;

trait HasCrawlingMiddleware
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    protected $tries = 1000;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    protected $maxExceptions = 3;

    public function retryUntil(): \DateTime
    {
        return now()->addDay();
    }

    public function middleware()
    {
        if ('testing' === config('app.env')) {
            return [];
        }

        $rateLimitedMiddleware = (new RateLimited)
            ->allow(Application::getInt('xcity', 'jobs_in_second', 2))
            ->everySecond() // In second
            ->releaseAfterMinutes(Application::getInt('xcity', 'release_jobs_after_minutes', 1));

        return [$rateLimitedMiddleware];
    }
}
