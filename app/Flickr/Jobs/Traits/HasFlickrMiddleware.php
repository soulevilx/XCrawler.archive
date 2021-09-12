<?php

namespace App\Flickr\Jobs\Traits;

use Spatie\RateLimitedMiddleware\RateLimited;

trait HasFlickrMiddleware
{
    protected $tries = 5;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    protected $maxExceptions = 3;

    protected int $allow = 3000;
    protected int $releaseAfterMinutes = 30;

    public function retryUntil(): \DateTime
    {
        return now()->addDay();
    }

    public function middleware()
    {
        $rateLimitedMiddleware = (new RateLimited())
            ->key('api:flickr')
            ->allow($this->allow) // Allow  job
            ->everyMinute() // In second
            ->releaseAfterMinutes($this->releaseAfterMinutes); // Release back to pool

        return [$rateLimitedMiddleware];
    }
}
