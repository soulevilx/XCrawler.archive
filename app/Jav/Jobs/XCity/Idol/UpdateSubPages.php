<?php

namespace App\Jav\Jobs\XCity\Idol;

use App\Core\Jobs\BaseJob;
use App\Core\Services\Facades\Application;
use App\Jav\Crawlers\XCityIdolCrawler;
use App\Jav\Services\XCityIdolService;

class UpdateSubPages extends BaseJob
{
    protected string $serviceName = 'xcity';

    public function handle(XCityIdolCrawler $crawler)
    {
        Application::setSetting(
            XCityIdolService::SERVICE_NAME,
            'sub_pages',
            $crawler->getSubPages()->toArray()
        );
    }
}
