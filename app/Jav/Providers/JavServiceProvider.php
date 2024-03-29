<?php

namespace App\Jav\Providers;

use App\Core\Providers\BaseServiceProvider;
use App\Core\XCrawlerClient;
use App\Jav\Crawlers\OnejavCrawler;
use App\Jav\Crawlers\R18Crawler;
use App\Jav\Crawlers\SCuteCrawler;
use App\Jav\Crawlers\XCityIdolCrawler;
use App\Jav\Crawlers\XCityVideoCrawler;
use App\Jav\Services\OnejavService;
use App\Jav\Services\R18Service;
use App\Jav\Services\SCuteService;
use App\Jav\Services\XCityIdolService;
use App\Jav\Services\XCityVideoService;
use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\Response\JsonResponse;

class JavServiceProvider extends BaseServiceProvider
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

    protected array $routes = [
        __DIR__ . '/../Routes/jav_routes.php'
    ];

    public function register()
    {
        parent::register();

        // Onejav
        $this->app->bind(OnejavCrawler::class, function () {
            $client = new XCrawlerClient(OnejavService::SERVICE_NAME, new DomResponse());

            return new OnejavCrawler($client);
        });

        $this->app->bind(R18Crawler::class, function () {
            return new R18Crawler(
                new XCrawlerClient(R18Service::SERVICE_NAME, new DomResponse()),
                new XCrawlerClient(R18Service::SERVICE_NAME, new JsonResponse())
            );
        });

        $this->app->bind(XCityIdolCrawler::class, function () {
            return new XCityIdolCrawler(new XCrawlerClient(XCityIdolService::SERVICE_NAME, new DomResponse()));
        });

        $this->app->bind(XCityVideoCrawler::class, function () {
            return new XCityVideoCrawler(new XCrawlerClient(XCityVideoService::SERVICE_NAME, new DomResponse()));
        });

        $this->app->bind(SCuteCrawler::class, function () {
           return new SCuteCrawler(new XCrawlerClient(SCuteService::SERVICE_NAME, new DomResponse()));
        });
    }
}
