<?php

namespace App\Flickr\Jobs;

use App\Core\Jobs\AbstractStandardJob;
use Spatie\RateLimitedMiddleware\RateLimited;

abstract class AbstractLimitJob extends AbstractStandardJob
{
    protected int $allow = 3000;
    protected int $releaseAfterMinutes = 60;
    protected int $everyMinutes = 60;

    public const JOB_KEY = 'api:flickr';

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
            ->key(self::JOB_KEY)
            ->allow($this->allow) // Allow  job
            ->everyMinutes($this->everyMinutes) // In minutes
            ->releaseAfterMinutes($this->releaseAfterMinutes); // Release back to pool

        return [$rateLimitedMiddleware];
    }
}
