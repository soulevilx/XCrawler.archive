<?php

namespace App\Jav\Observers;

use App\Jav\Events\XCityVideoCompleted;
use App\Jav\Models\XCityVideo;
use Illuminate\Support\Facades\Event;

class XCityVideoObserver
{
    public function updated(XCityVideo $model)
    {
        if ($model->isDirty('state_code') && $model->isCompletedState()) {
            Event::dispatch(new XCityVideoCompleted($model));
        }
    }
}
