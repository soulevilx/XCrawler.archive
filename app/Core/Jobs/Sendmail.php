<?php

namespace App\Core\Jobs;

use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Spatie\RateLimitedMiddleware\RateLimited;

class Sendmail implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public Mailable $mailable)
    {
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return DateTime
     */
    public function retryUntil()
    {
        return now()->addHours(12);
    }

    /**
     * Attempt 1: Release after 60 seconds
     * Attempt 2: Release after 180 seconds
     * Attempt 3: Release after 420 seconds
     * Attempt 4: Release after 900 seconds.
     */
    public function middleware()
    {
        if ('testing' !== config('app.env')) {
            $rateLimitedMiddleware = (new RateLimited())
                ->allow(4) // Allow 2 jobs
                ->everySecond() // In second
                ->releaseAfterMinutes(30) // Release back to pool after 30 minutes
            ;

            return [$rateLimitedMiddleware];
        }

        return [];
    }

    public function handle()
    {
        Mail::send($this->mailable);
    }
}
