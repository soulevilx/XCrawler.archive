<?php

namespace App\Jav\Jobs\XCity\Idol;

use App\Core\Jobs\BaseJob;
use App\Core\Services\Facades\Application;
use App\Jav\Crawlers\XCityIdolCrawler;
use App\Jav\Models\XCityIdol;
use App\Jav\Services\XCityIdolService;

class UpdatePagesCount extends BaseJob
{
    protected string $serviceName = 'xcity';

    public function __construct(public string $kana)
    {
    }

    public function handle(XCityIdolCrawler $crawler)
    {
        $totalPages = $crawler->getPages(XCityIdol::INDEX_URL, ['kana' => $this->kana]);
        Application::setSetting(XCityIdolService::SERVICE_NAME, $this->kana.'_total_pages', $totalPages);
    }
}
