<?php

namespace App\Core\Services\Facades;

use App\Core\Services\ApplicationService;
use Illuminate\Support\Facades\Facade;

class Application extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ApplicationService::class;
    }
}
