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

    public function retryUntil(): \DateTime
    {
        return now()->addMinutes(5);
    }

    public function middleware()
    {
        if ('testing' === config('app.env')) {
            return [];
        }

        $rateLimitedMiddleware = (new RateLimited())
            ->allow($this->allow) // Allow 1 jobs
            ->everySecond() // In second
            ->releaseAfterSeconds(60) // Release back to pool after 5 minutes
        ;

        return [$rateLimitedMiddleware];
    }
}
