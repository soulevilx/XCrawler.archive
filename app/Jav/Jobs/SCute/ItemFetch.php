<?php

namespace App\Jav\Jobs\SCute;

use App\Jav\Models\SCute;
use App\Jav\Services\SCuteService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class ItemFetch
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct(public SCute $model)
    {
    }

    public function handle(SCuteService $service)
    {
        $service->item($this->model);
    }
}
