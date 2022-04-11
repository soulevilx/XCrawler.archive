<?php

namespace App\Jav\Jobs\R18;

use App\Core\Models\State;
use App\Jav\Jobs\Traits\R18CrawlingMiddleware;
use App\Jav\Models\R18;
use App\Jav\Services\R18Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ItemFetch implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use R18CrawlingMiddleware;

    public function __construct(public R18 $model)
    {
        $model->setState(State::STATE_PROCESSING);
    }

    public function handle(R18Service $service)
    {
        $this->model = $service->item($this->model);
        $this->model->update([
            'state_code' => State::STATE_COMPLETED,
        ]);
    }

    public function failed()
    {
        $this->model->setState(State::STATE_INIT);
    }
}
