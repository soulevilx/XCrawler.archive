<?php

namespace App\Jav\Services;

use App\Core\Models\Download;
use App\Core\Services\ApplicationService;
use App\Jav\Crawlers\OnejavCrawler;
use App\Jav\Events\Onejav\OnejavDailyCompleted;
use App\Jav\Events\Onejav\OnejavReleaseCompleted;
use App\Jav\Events\OnejavDownloadCompleted;
use App\Jav\Models\Onejav;
use App\Jav\Repositories\OnejavRespository;
use App\Jav\Services\Interfaces\ServiceInterface;
use App\Jav\Services\Traits\HasAttributes;
use ArrayObject;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;

class OnejavService
{
    use HasAttributes;

    public const SERVICE_NAME = 'onejav';
    public const DAILY_FORMAT = 'Y/m/d';

    public function __construct(protected OnejavCrawler $crawler, protected OnejavRespository $repository)
    {
    }

    public function create(array $attributes): Onejav
    {
        return $this->repository->updateOrCreate([
            'dvd_id' => $attributes['dvd_id'],
        ], $attributes);
    }

    public function daily()
    {
        $items = $this->crawler->daily();

        if ($items->isEmpty()) {
            return $items;
        }

        $items->each(function ($item) {
            /**
             * @var ArrayObject $item
             */
            $this->repository->updateOrCreate(
                ['url' => $item->url],
                $item->getArrayCopy()
            );
        });

        Event::dispatch(new OnejavDailyCompleted($items));

        return $items;
    }

    public function release()
    {
        $currentPage = ApplicationService::getConfig('onejav', 'current_page', 1);

        $items = $this->crawler->getItems('new', ['page' => $currentPage]);

        $items->each(function ($item) {
            $this->repository->updateOrCreate(
                ['url' => $item->url],
                $item->getArrayCopy()
            );
        });

        ++$currentPage;

        if ((int) ApplicationService::getConfig('onejav', 'total_pages', config('services.onejav.total_pages')) < $currentPage) {
            $currentPage = 1;
            Event::dispatch(new OnejavReleaseCompleted($items));
        }

        ApplicationService::setConfig('onejav', 'current_page', $currentPage);

        return $items;
    }

    public function item(Model $model): Onejav
    {
        return $this->refetch($model);
    }

    public function download(Onejav $onejav): bool
    {
        $onejav = $this->refetch($onejav);

        $fileName = config('services.jav.download_dir') . '/' . basename($onejav->torrent);
        $file = fopen($fileName, 'wb');
        $response = app(Client::class)->request(
            'GET',
            $onejav->torrent,
            [
                'sink' => $file,
                'base_uri' => Onejav::BASE_URL,
            ]
        );

        if ($response->getStatusCode() === 200) {
            Download::create([
                'model_id' => $onejav->id,
                'model_type' => Onejav::class,
            ]);

            Event::dispatch(new OnejavDownloadCompleted($onejav));

            return true;
        }

        return false;
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
