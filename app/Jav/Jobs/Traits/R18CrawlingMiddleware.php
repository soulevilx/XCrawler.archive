<?php

namespace App\Jav\Jobs\Traits;

use Spatie\RateLimitedMiddleware\RateLimited;

trait R18CrawlingMiddleware
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

    protected int $allow = 2;
    protected int $releaseAfterSeconds = 2;

    public function retryUntil(): \DateTime
    {
        return now()->addDay();
    }

    public function middleware()
    {
        if ('testing' === config('app.env')) {
            return [];
        }

        $rateLimitedMiddleware = (new RateLimited())
            ->allow($this->allow) // Allow 2 job
            ->everySecond() // In second
            ->releaseAfterMinutes(1); // Release back to pool

        return [$rateLimitedMiddleware];
    }
}
