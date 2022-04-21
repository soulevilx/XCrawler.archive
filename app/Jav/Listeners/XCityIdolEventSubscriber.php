<?php

namespace App\Jav\Listeners;

use App\Jav\Events\XCity\XCityIdolCompleted;
use App\Jav\Models\Performer;
use Illuminate\Events\Dispatcher;

class XCityIdolEventSubscriber
{
    public function onIdolCompleted(XCityIdolCompleted $event)
    {
        $model = $event->model;
        if (!$model->name) {
            return;
        }

        Performer::firstOrCreate([
            'name' => $model->name,
            ], [
            'cover' => $model->cover,
            'city' => $model->city,
            'height' => $model->height,
            'breast' => $model->breast,
            'waist' => $model->waist,
            'hips' => $model->hips,
        ]);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            [XCityIdolCompleted::class],
            self::class.'@onIdolCompleted'
        );
    }
}
