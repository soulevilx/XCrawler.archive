<?php

namespace App\Jav\Jobs\R18;

use App\Core\Models\State;
use App\Jav\Models\R18;
use App\Jav\Services\R18Service;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\RateLimitedMiddleware\RateLimited;

class ItemFetch implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public R18 $model)
    {
        $model->setState(State::STATE_PROCESSING);
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return DateTime
     */
    public function retryUntil()
    {
        return now()->addHours(6);
    }

    public function middleware()
    {
        if ('testing' !== config('app.env')) {
            $rateLimitedMiddleware = (new RateLimited())
                ->allow(2) // Allow 2 jobs
                ->everySecond() // In second
                ->releaseAfterMinutes(15) // Release back to pool after 15 minutes
            ;

            return [$rateLimitedMiddleware];
        }

        return [];
    }

    public function handle(R18Service $service)
    {
        $this->model = $service->item($this->model);
        $this->model->update([
            'state_code' => State::STATE_COMPLETED,
        ]);
    }
}
