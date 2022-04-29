<?php

namespace App\Jav\Tests\Unit\Listeners;

use App\Jav\Events\Onejav\OnejavDailyCompleted;
use App\Jav\Events\Onejav\OnejavReleaseCompleted;
use App\Jav\Notifications\OnejavCompletedNotification;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OnejavEventSubscriberTest extends TestCase
{
    public function testNotificationsOnOnejavDailyCompleted()
    {
        Event::dispatch(new OnejavDailyCompleted(collect()));

        Notification::assertSentTo(new AnonymousNotifiable, OnejavCompletedNotification::class);
    }

//    public function testNotificationsOnOnejavReleaseCompleted()
//    {
//        Event::dispatch(new OnejavReleaseCompleted(collect()));
//
//        Notification::assertSentTo(new AnonymousNotifiable, OnejavCompletedNotification::class);
//    }
}
