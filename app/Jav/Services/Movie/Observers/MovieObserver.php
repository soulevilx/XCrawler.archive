<?php

namespace App\Jav\Services\Movie\Observers;

use App\Jav\Models\Interfaces\MovieInterface;
use App\Jav\Services\Movie\MovieService;

class MovieObserver
{
    public function __construct(private MovieService $service)
    {
    }

    /**
     * Handle created event.
     */
    public function created(MovieInterface $model)
    {
        if (method_exists($model, 'isCompletedState') && $model->isCompletedState()) {
            $this->service->create($model);
        }
    }

    public function updated(MovieInterface $model)
    {
        if ($model->isDirty('state_code') && $model->isCompletedState()) {
            $this->service->create($model);
        }
    }
}
