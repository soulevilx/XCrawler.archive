<?php

namespace App\Jav\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class OnejavCompletedNotifications extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Collection $items)
    {
    }
}
