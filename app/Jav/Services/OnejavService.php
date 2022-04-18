<?php

namespace App\Jav\Services;

use App\Core\Models\Download;
use App\Core\Services\Facades\Application;
use App\Core\Services\MediaService;
use App\Jav\Crawlers\OnejavCrawler;
use App\Jav\Events\Onejav\OnejavDailyCompleted;
use App\Jav\Events\Onejav\OnejavDownloadCompleted;
use App\Jav\Events\Onejav\OnejavReleaseCompleted;
use App\Jav\Models\Onejav;
use App\Jav\Repositories\OnejavRespository;
use App\Jav\Services\Traits\HasAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;

class OnejavService
{
    use HasAttributes;

    public const SERVICE_NAME = 'onejav';
    public const DAILY_FORMAT = 'Y/m/d';

    public const BASE_URL = 'https://onejav.com';

    public function __construct(protected OnejavCrawler $crawler, protected OnejavRespository $repository)
    {
    }

    public function create(array $attributes): Onejav
    {
        return $this->repository->create($attributes);
    }

    public function daily()
    {
        $items = $this->crawler->daily();
        $this->repository->createItems($items);

        Event::dispatch(new OnejavDailyCompleted($items));

        return $items;
    }

    public function release()
    {
        $currentPage = Application::getInt(OnejavService::SERVICE_NAME, 'current_page', 1);

        $items = $this->crawler->getItems('new', ['page' => $currentPage]);

        $this->repository->createItems($items);

        ++$currentPage;

        if (Application::getInt(OnejavService::SERVICE_NAME, 'total_pages', 8500) < $currentPage) {
            $currentPage = 1;
        }

        Application::setSetting(OnejavService::SERVICE_NAME, 'current_page', $currentPage);

        Event::dispatch(new OnejavReleaseCompleted($items));

        return $items;
    }

    public function item(Model $model): Onejav
    {
        return $this->refetch($model);
    }

    public function download(Onejav $onejav): bool
    {
        if ($onejav->downloads()->exists()) {
            return false;
        }

        $onejav = $this->refetch($onejav);

        $fileName = app(MediaService::class)->download(OnejavService::SERVICE_NAME, $onejav->torrent);
        if ($fileName === false) {
            return false;
        }

        /**
         * @TODO Move to Repository
         */
        Download::create([
            'model_id' => $onejav->id,
            'model_type' => Onejav::class,
        ]);

        Event::dispatch(new OnejavDownloadCompleted($onejav));

        return $fileName;
    }

    public function refetch(Onejav $onejav): Onejav
    {
        $item = $this->crawler->getItems($onejav->url)->first();
        $onejav->update($item->getArrayCopy());

        /**
         * @TODO
         * If refetch changing dvd_id we'll lost connect with Movie
         */
        return $onejav;
    }
}
