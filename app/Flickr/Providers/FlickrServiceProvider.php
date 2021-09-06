<?php

namespace App\Flickr\Providers;

use App\Core\Providers\BaseServiceProvider;
use App\Flickr\Services\FlickrService;

class FlickrServiceProvider extends BaseServiceProvider
{
    protected array $migrations = [
        __DIR__ . '/../Database/Migrations',
        __DIR__ . '/../Database/Seeders',
    ];

    protected array $configs = [
        __DIR__ . '/../Config' => [
            'services',
        ],
    ];

    protected $routes = [
        __DIR__ . '/../Routes/flickr_routes.php'
    ];

    public function register()
    {
        parent::register();

        $this->app->bind(FlickrService::class, function () {
            return new FlickrService(config('services.flickr.api_key'), config('services.flickr.secret_key'));
        });

    }
}
