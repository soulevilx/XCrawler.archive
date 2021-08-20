<?php

namespace App\Core\Providers;

use App\Core\Listeners\ClientRequestEventSubscriber;

class CoreServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->loadConfigs(__DIR__.'/../Config', ['services']);
    }
}
