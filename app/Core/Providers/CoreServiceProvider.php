<?php

namespace App\Core\Providers;

use App\Core\Services\ApplicationService;
use Illuminate\Support\Facades\App;

class CoreServiceProvider extends BaseServiceProvider
{
    protected array $migrations = [
        __DIR__ . '/../Database/Migrations',
        __DIR__ . '/../Database/Seeders',
    ];

    protected array $configs = [
        __DIR__ . '/../Config' => [
            'services',
            'core'
        ],
    ];

    protected array $routes = [
        __DIR__ . '/../Routes/core_routes.php'
    ];

    public function boot()
    {
        parent::boot();

        App::bind(ApplicationService::class, function () {
            return new ApplicationService;
        });
    }
}
