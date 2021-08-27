<?php

namespace App\Jav\Providers;

use App\Core\Client;
use App\Core\Providers\BaseServiceProvider;
use App\Jav\Crawlers\OnejavCrawler;
use App\Jav\Crawlers\R18Crawler;
use App\Jav\Crawlers\XCityIdolCrawler;
use App\Jav\Models\Onejav;
use App\Jav\Models\R18;
use App\Jav\Models\XCityIdol;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Queue;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\Response\JsonResponse;

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

        $this->app->bind(OnejavCrawler::class, function () {
            $client = app(Client::class)
                ->init(
                    Onejav::SERVICE,
                    new DomResponse(),
                )
            ;

            return new OnejavCrawler($client);
        });

        $this->app->bind(R18Crawler::class, function () {
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

        $this->app->bind(XCityIdolCrawler::class, function () {
            $client = app(Client::class)
                ->init(
                    XCityIdol::SERVICE,
                    new DomResponse(),
                )
            ;

            return new XCityIdolCrawler($client);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Queue::before(function (JobProcessing $event) {
            // $event->connectionName
            // $event->job
            // $event->job->payload()
        });

        Queue::after(function (JobProcessed $event) {
            // $event->connectionName
            // $event->job
            // $event->job->payload()
        });
        Queue::failing(function (JobFailed $event) {
            // $event->connectionName
            // $event->job
            // $event->exception
        });
    }
}
