<?php

namespace App\Core\Jobs\Middlewares;

use App\Core\Models\Queue;
use App\Core\Services\Facades\Application;
use Illuminate\Support\Facades\Redis;

class XCrawlerMiddleware
{
    public function __construct(private string $class, private string $service)
    {
    }

    /**
     * Process the queued job.
     *
     * @param  mixed  $job
     * @param  callable  $next
     * @return mixed
     */
    public function handle($job, $next)
    {
        Redis::throttle($this->generateKey())
            ->block(Application::getInt($this->service, 'middleware.block', 0))
            ->allow(Application::getInt($this->service, 'middleware.allow', 1))
            ->every(Application::getInt($this->service, 'middleware.every', 1))
            ->then(function () use ($job, $next) {
                Queue::create([
                    'payload' => $job->job->payload(),
                ]);
                $next($job);
            }, function () use ($job) {
                // Could not obtain lock...

                $job->release(Application::getInt($this->service, 'middleware.release', 10));
            });
    }

    private function generateKey()
    {
        return md5(serialize([
            config('app.key'),
            $this->class,
            config('app.server_ip'),
        ]));
    }
}
