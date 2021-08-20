<?php

namespace App\Core\Providers;

class CoreServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->loadConfigs(__DIR__.'/../Config', ['services']);
    }
}
