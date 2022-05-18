<?php

namespace App\Jav\Jobs\XCity\Idol;

use App\Core\Jobs\BaseJob;
use App\Core\Services\Facades\Application;
use App\Jav\Crawlers\XCityIdolCrawler;
use App\Jav\Models\XCityIdol;
use App\Jav\Services\XCityIdolService;

class FetchIdolLinks extends BaseJob
{
    protected string $service = 'xcity';

    public function __construct(public string $kana, public int $page = 1, public bool $updateCurrentPage = true)
    {
    }

    public function handle(XCityIdolCrawler $crawler, XCityIdolService $service)
    {
        $configKey = $this->kana.'_current_page';

        $this->page = $this->updateCurrentPage
            ? Application::getSetting(XCityIdolService::SERVICE_NAME, $configKey, $this->page)
            : $this->page;

        $links = $crawler->getItemLinks(XCityIdol::INDEX_URL, ['kana' => $this->kana, 'page' => $this->page]);

        $links->each(function ($link) use ($service) {
            $service->create([
                'url' => $link,
            ]);
        });

        if (!$this->updateCurrentPage) {
            return;
        }

        ++$this->page;

        $totalPages = (int) Application::getSetting(XCityIdolService::SERVICE_NAME, $this->kana.'_total_pages', 1);
        if ($this->page > $totalPages) {
            $this->page = 1;
        }

        Application::setSetting(XCityIdolService::SERVICE_NAME, $configKey, $this->page);
    }
}
