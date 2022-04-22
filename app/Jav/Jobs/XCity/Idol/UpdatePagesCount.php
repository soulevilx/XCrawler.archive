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

class UpdatePagesCount implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use HasCrawlingMiddleware;

    public function __construct(public string $kana)
    {
    }

    public function handle(XCityIdolCrawler $crawler)
    {
        $totalPages = $crawler->getPages(XCityIdol::INDEX_URL, ['kana' => $this->kana]);
        Application::setSetting(XCityIdolService::SERVICE_NAME, $this->kana.'_total_pages', $totalPages);
    }
}
