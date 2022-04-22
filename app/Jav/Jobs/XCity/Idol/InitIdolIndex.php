<?php

namespace App\Jav\Jobs\XCity\Idol;

use App\Core\Services\Facades\Application;
use App\Jav\Crawlers\XCityIdolCrawler;
use App\Jav\Jobs\Traits\HasCrawlingMiddleware;
use App\Jav\Models\XCityIdol;
use App\Jav\Services\XCityIdolService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

/**
 * This job will be used to fetch total pages of an index view.
 */
class InitIdolIndex implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use HasCrawlingMiddleware;

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
