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
    public $tries = 5;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 3;

    protected int $allow = 1;
    protected int $releaseAfterSeconds = 60;

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
            ->allow($this->allow) // Allow 1 jobs
            ->everySecond() // In second
            ->releaseAfterSeconds($this->releaseAfterSeconds) // Release back to pool
            ->releaseAfterBackoff($this->attempts())
        ;

        return [$rateLimitedMiddleware];
    }
}
