<?php

namespace App\Core\Providers;

class CoreServiceProvider extends BaseServiceProvider
{
    protected array $migrations = [
        __DIR__.'/../Database/Migrations',
        __DIR__.'/../Database/Seeders',
    ];

    protected array $configs = [
        __DIR__.'/../Config' => [
            'services',
            'core'
        ],
    ];

    protected array $routes = [
        __DIR__ .'/../Routes/core_routes.php'
    ];
}
