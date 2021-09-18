<?php

namespace App\Core\Providers;

use App\Core\Listeners\ClientRequestEventSubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class CoreEventServiceProvider extends EventServiceProvider
{
    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        ClientRequestEventSubscriber::class,
    ];
}
