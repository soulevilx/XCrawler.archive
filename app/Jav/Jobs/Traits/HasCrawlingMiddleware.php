<?php

namespace App\Jav\Jobs\Traits;

use Spatie\RateLimitedMiddleware\RateLimited;

trait HasCrawlingMiddleware
{
    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addHour();
    }

    public function middleware()
    {
        if ('testing' === config('app.env')) {
            return [];
        }

        $rateLimitedMiddleware = (new RateLimited())
            ->allow($this->allow) // Allow 1 jobs
            ->everySecond() // In second
            ->releaseAfterMinutes($this->releaseAfterMinutes) // Release back to pool after 5 minutes
        ;

        return [$rateLimitedMiddleware];
    }
}
