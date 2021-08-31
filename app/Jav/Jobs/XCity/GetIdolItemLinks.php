<?php

namespace App\Jav\Jobs\XCity;

use App\Core\Services\ApplicationService;
use App\Jav\Crawlers\XCityIdolCrawler;
use App\Jav\Jobs\Traits\HasCrawlingMiddleware;
use App\Jav\Models\XCityIdol;
use App\Jav\Services\XCityIdolService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class GetIdolItemLinks implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use HasCrawlingMiddleware;

    public function __construct(public string $kana, public int $page = 1, public bool $updateCurrentPage = true)
    {
    }

    public function handle(XCityIdolCrawler $crawler, XCityIdolService $service)
    {
        $configKey = $this->kana.'_current_page';
        $totalPages = (int) ApplicationService::getConfig('xcity', $this->kana.'_total_pages', 1);

        $this->page = $this->updateCurrentPage
            ? ApplicationService::getConfig('xcity', $configKey, $this->page)
            : $this->page;

        $links = $crawler->getItemLinks(XCityIdol::INDEX_URL, ['kana' => $this->kana, 'page' => $this->page]);

        $links->each(function ($link) use ($service) {
            $service->setAttributes([
                'url' => $link,
            ])->create();
        });

        if (!$this->updateCurrentPage) {
            return;
        }

        ++$this->page;

        if ($this->page > $totalPages) {
            $this->page = 1;
        }

        ApplicationService::setConfig('xcity', $configKey, $this->page);
    }
}
