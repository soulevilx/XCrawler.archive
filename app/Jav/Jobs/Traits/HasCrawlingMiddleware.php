<?php

namespace App\Jav\Jobs\Traits;

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

    protected int $allow = 1;
    protected int $releaseAfterSeconds = 2;

    public function retryUntil(): \DateTime
    {
        return now()->addDay();
    }

    public function middleware()
    {
        if (app()->environment('testing')) {
            return [];
        }

        $rateLimitedMiddleware = (new RateLimited())
            ->allow($this->allow) // Allow 1 job
            ->everySecond() // In second
            ->releaseAfterMinutes(1); // Release back to pool

        return [$rateLimitedMiddleware];
    }
}
