<?php

namespace App\Jav\Jobs\XCity\Idol;

use App\Core\Jobs\BaseJob;
use App\Core\Services\Facades\Application;
use App\Jav\Crawlers\XCityIdolCrawler;
use App\Jav\Models\XCityIdol;
use App\Jav\Services\XCityIdolService;

/**
 * This job will be used to fetch total pages of an index view.
 */
class InitIdolIndex extends BaseJob
{
    protected string $service = 'xcity';

    public function __construct(public string $kana, public int $page = 1)
    {
    }

    public function handle(XCityIdolCrawler $crawler)
    {
        $configKey = $this->kana.'_total_pages';

        if (Application::getSetting(XCityIdolService::SERVICE_NAME, $configKey)) {
            return;
        }

        $totalPages = $crawler->getPages(XCityIdol::INDEX_URL, ['kana' => $this->kana]);
        Application::getSetting(XCityIdolService::SERVICE_NAME, $configKey, $totalPages);
    }
}
