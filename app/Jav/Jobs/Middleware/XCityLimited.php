<?php

namespace App\Jav\Jobs\Middleware;

use Illuminate\Support\Facades\Redis;

class XCityLimited
{
    protected int $allow = 1;
    protected int $everySeconds = 1;
    protected int $releaseAfterSeconds = 10;

    /**
     * Process the queued job.
     *
     * @param mixed    $job
     * @param callable $next
     *
     * @return mixed
     */
    public function handle($job, $next)
    {
        Redis::throttle('xcity')
            ->block(0) //  Set the amount of time to block until a lock is available.
            ->allow($this->allow)
            ->every($this->everySeconds)
            ->then(function () use ($job, $next) {
                $next($job);
            }, function () use ($job) {
                $job->release($this->releaseAfterSeconds);
            })
        ;
    }
}
