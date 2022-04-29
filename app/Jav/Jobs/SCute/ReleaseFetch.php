<?php

namespace App\Jav\Jobs\SCute;

use App\Jav\Services\SCuteService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class ReleaseFetch
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function handle(SCuteService $service)
    {
        $service->release();
    }
}
