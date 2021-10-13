<?php

namespace App\Jav\Jobs\R18;

use App\Jav\Jobs\Traits\HasCrawlingMiddleware;
use App\Jav\Services\R18Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class ReleaseFetch implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use HasCrawlingMiddleware;

    public function __construct(public string $type = 'release')
    {
    }

    public function handle(R18Service $service)
    {
        $service->release($this->type);
    }
}
