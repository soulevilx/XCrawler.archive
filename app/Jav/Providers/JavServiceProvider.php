<?php

namespace App\Jav\Providers;

use App\Core\Client;
use App\Core\Providers\BaseServiceProvider;
use App\Jav\Crawlers\OnejavCrawler;
use App\Jav\Models\Onejav;
use Jooservices\XcrawlerClient\Response\DomResponse;

class JavServiceProvider extends BaseServiceProvider
{
    protected array $migrations = [
        __DIR__.'/../Database/Migrations',
        __DIR__.'/../Database/Seeders',
    ];

    protected array $configs = [
        __DIR__.'/../Config' => [
            'services',
        ],
    ];

    public function register()
    {
        parent::register();

        $this->app->bind(OnejavCrawler::class, function ($app) {
            $client = app(Client::class)
                ->init(
                    Onejav::SERVICE,
                    new DomResponse(),
                )
            ;

            return new OnejavCrawler($client);
        });
    }
}
