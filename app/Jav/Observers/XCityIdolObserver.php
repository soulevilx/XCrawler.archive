<?php

namespace App\Jav\Observers;

use App\Jav\Events\XCity\XCityIdolCompleted;
use App\Jav\Models\XCityIdol;
use Illuminate\Support\Facades\Event;

class XCityIdolObserver
{
    public function updated(XCityIdol $model)
    {
        if ($model->isDirty('state_code') && $model->isCompletedState()) {
            Event::dispatch(new XCityIdolCompleted($model));
        }
    }
}
