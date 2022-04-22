<?php

namespace App\Jav\Services;

use App\Core\Services\Facades\Application;
use App\Jav\Crawlers\XCityIdolCrawler;
use App\Jav\Jobs\XCity\Idol\FetchIdolLinks;
use App\Jav\Jobs\XCity\Idol\InitIdolIndex;
use App\Jav\Models\XCityIdol;
use App\Jav\Repositories\XCityIdolRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;

class XCityIdolService
{
    public const SERVICE_NAME = 'xcity_idols';
    public const BASE_URL = 'https://xxx.xcity.jp';
    public const SUBPAGES = [
        "/idol/?kana=あ",
        "/idol/?kana=か",
        "/idol/?kana=さ",
        "/idol/?kana=た",
        "/idol/?kana=な",
        "/idol/?kana=は",
        "/idol/?kana=ま",
        "/idol/?kana=や",
        "/idol/?kana=ら",
        "/idol/?kana=わ",
    ];

    public const QUEUE_NAME = 'crawling';

    public function __construct(protected XCityIdolCrawler $crawler, protected XCityIdolRepository $repository)
    {
    }

    public function getSubPages(): array
    {
        $subPages = Application::getArray(self::SERVICE_NAME, 'sub_pages');

        if (empty($subPages)) {
            $subPages = $this->crawler->getSubPages()->toArray();
            Application::setSetting(self::SERVICE_NAME, 'sub_pages', $subPages);
        }

        return $subPages;
    }

    public function daily()
    {
        $subPages = $this->getSubPages();
        foreach ($subPages as $subPage) {
            $kana = str_replace('/idol/?kana=', '', $subPage);
            FetchIdolLinks::dispatch($kana, 1, false)->onQueue('crawling');
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
                /**
                 * If settings is not inited then we'll crawl it
                 * We also have another job for updating this setting
                 */
                new InitIdolIndex($kana),
                new FetchIdolLinks($kana),
            ])->onQueue(self::QUEUE_NAME)->dispatch();
        }
    }

    public function create(array $attributes): XCityIdol
    {
        return $this->repository->create($attributes);
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

    public function getItems(int $limit, int $id = null): Collection
    {
        return $this->repository->getItemsByState($limit, $id);
    }
}
