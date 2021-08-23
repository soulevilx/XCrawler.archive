<?php

namespace App\Jav\Providers;

use App\Core\Client;
use App\Core\Providers\BaseServiceProvider;
use App\Jav\Crawlers\OnejavCrawler;
use App\Jav\Crawlers\R18Crawler;
use App\Jav\Models\Onejav;
use App\Jav\Models\R18;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\Response\JsonResponse;
use Jooservices\XcrawlerClient\Settings\RequestOptions;

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

        $this->app->bind(R18Crawler::class, function ($app) {
            $domClient = app(Client::class)
                ->init(
                    R18::SERVICE,
                    new DomResponse(),
                )
            ;

            $jsonClient = app(Client::class)
                ->init(
                    R18::SERVICE,
                    new JsonResponse(),
                )
            ;

            return new R18Crawler($domClient, $jsonClient);
        });
    }
}
