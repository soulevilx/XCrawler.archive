<?php

namespace App\Jav\Services;

use App\Core\Models\State;
use App\Core\Services\ApplicationService;
use App\Jav\Crawlers\XCityIdolCrawler;
use App\Jav\Jobs\XCity\GetIdolItemLinks;
use App\Jav\Jobs\XCity\InitIdolIndex;
use App\Jav\Models\XCityIdol;
use App\Jav\Services\Traits\HasAttributes;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;

class XCityService
{
    use HasAttributes;

    protected XCityIdol $idol;

    public function __construct(protected XCityIdolCrawler $crawler, protected ApplicationService $service)
    {
    }

    public function getSubPages()
    {
        if (Cache::has('xcity_idols_sub_pages')) {
            return Cache::get('xcity_idols_sub_pages');
        }

        $subPages = $this->crawler->getSubPages();
        Cache::put('xcity_idols_sub_pages', $subPages, now()->addDays(7));

        return $subPages;
    }

    public function daily()
    {
        $subPages = $this->getSubPages();
        foreach ($subPages as $subPage) {
            $kana = str_replace('/idol/?kana=', '', $subPage);
            GetIdolItemLinks::dispatch($kana, 1, false)->onQueue('crawling');
        }
    }

    public function release()
    {
        /**
         * Step 1 : Get sub pages
         * Step 2 : Process each page by `kana`
         * Step 3 : Bus chain with
         * - Fetch index and get pages count
         * - Get links and update current page.
         */
        $subPages = $this->getSubPages();
        foreach ($subPages as $subPage) {
            $kana = str_replace('/idol/?kana=', '', $subPage);
            Bus::chain([
                new InitIdolIndex($kana),
                new GetIdolItemLinks($kana),
            ])->onQueue('crawling')->dispatch();
        }
    }

    public function create()
    {
        $this->defaultAttribute('state_code', State::STATE_INIT);

        $this->idol = XCityIdol::firstOrCreate([
            'url' => $this->attributes['url'],
        ], $this->attributes);
    }
}
