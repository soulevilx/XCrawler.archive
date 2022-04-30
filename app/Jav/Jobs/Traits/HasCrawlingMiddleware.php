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
            ->allow(config('services.jav.jobs_per_second') ?? Application::getInt('jav', 'jobs_in_second', 2))
            ->everySecond() // In second
            ->releaseAfterMinutes(config('services.jav.release_jobs_after_minutes') ?? Application::getInt('jav', 'release_jobs_after_minutes', 1));

        return [$rateLimitedMiddleware];
    }
}
