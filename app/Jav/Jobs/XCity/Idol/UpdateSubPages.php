<?php

namespace App\Jav\Jobs\XCity\Idol;

use App\Core\Services\Facades\Application;
use App\Jav\Crawlers\XCityIdolCrawler;
use App\Jav\Jobs\Traits\HasCrawlingMiddleware;
use App\Jav\Services\XCityIdolService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class UpdateSubPages implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use HasCrawlingMiddleware;

    public function handle(XCityIdolCrawler $crawler)
    {
        Application::setSetting(
            XCityIdolService::SERVICE_NAME,
            'sub_pages',
            $crawler->getSubPages()->toArray()
        );
    }
}
