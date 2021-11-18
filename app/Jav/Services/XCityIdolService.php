<?php

namespace App\Jav\Services;

use App\Core\Models\State;
use App\Core\Services\ApplicationService;
use App\Jav\Crawlers\XCityIdolCrawler;
use App\Jav\Jobs\XCity\GetIdolItemLinks;
use App\Jav\Jobs\XCity\InitIdolIndex;
use App\Jav\Models\XCityIdol;
use App\Jav\Services\Interfaces\ServiceInterface;
use App\Jav\Services\Traits\HasAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Bus;

class XCityIdolService implements ServiceInterface
{
    use HasAttributes;

    protected XCityIdol $idol;

    public const SERVICE_LABEL = 'XCity idols';

    public function __construct(protected XCityIdolCrawler $crawler, protected ApplicationService $service)
    {
    }

    public function getSubPages()
    {
        $subPages = ApplicationService::getConfig('xcity_idol', 'sub_pages');

        if (!$subPages) {
            $subPages = $this->crawler->getSubPages();
            ApplicationService::setConfig('xcity_idol', 'sub_pages', $subPages);
        }

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
        foreach ($subPages as $index => $subPage) {
            $kana = str_replace('/idol/?kana=', '', $subPage);
            Bus::chain([
                new InitIdolIndex($kana),
                new GetIdolItemLinks($kana),
            ])->onQueue('crawling')->dispatch();
        }
    }

    public function create(): XCityIdol
    {
        $this->defaultAttribute('state_code', State::STATE_INIT);

        $this->idol = XCityIdol::firstOrCreate([
            'url' => $this->attributes['url'],
        ], $this->attributes);

        return $this->idol;
    }

    public function item(Model $model): XCityIdol
    {
        return $this->refetch($model);
    }

    public function refetch(XCityIdol $model): XCityIdol
    {
        $id = trim(str_replace('detail/', '', $model->url), '/');
        if ($item = $this->crawler->getItem($id)) {
            $model->update($item->getArrayCopy());
        }

        return $model;
    }
}
