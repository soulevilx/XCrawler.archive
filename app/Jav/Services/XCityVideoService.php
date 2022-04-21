<?php

namespace App\Jav\Services;

use App\Core\Services\Facades\Application;
use App\Jav\Crawlers\XCityVideoCrawler;
use App\Jav\Jobs\XCity\InitVideoIndex;
use App\Jav\Models\XCityVideo;
use App\Jav\Repositories\XCityVideoRepository;
use App\Jav\Services\Traits\HasAttributes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class XCityVideoService
{
    public const SERVICE_NAME = 'xcity_videos';
    public const BASE_URL = 'https://xxx.xcity.jp';

    public function __construct(protected XCityVideoCrawler $crawler, protected XCityVideoRepository $repository)
    {
    }

    public function release()
    {
        $fromDate = Carbon::createFromFormat(
            'Ymd',
            Application::getSetting(
                XCityVideoService::SERVICE_NAME,
                'from_date',
                config('services.xcity_video.from_date', 20010101)
            )
        );

        $toDate = $fromDate->clone()->addDay();

        InitVideoIndex::dispatch([
            'from_date' => $fromDate->format('Ymd'),
            'to_date' => $toDate->format('Ymd'),
        ])->onQueue('crawling');

        Application::setSetting(XCityVideoService::SERVICE_NAME, 'from_date', $toDate->format('Ymd'));
    }

    public function create(array $attributes): XCityVideo
    {
        return $this->repository->create($attributes);
    }

    public function item(Model $model): XCityVideo
    {
        return $this->refetch($model);
    }

    public function refetch(XCityVideo $model): XCityVideo
    {
        $id = trim(str_replace('/avod/detail/?id=', '', $model->url), '/');
        if ($item = $this->crawler->getItem('/avod/detail/', ['id' => $id])) {
            $model->update($item->getArrayCopy());
        }

        return $model;
    }

    public function daily()
    {
        $fromDate = Carbon::now();
        InitVideoIndex::dispatch([
            'from_date' => $fromDate->format('Ymd'),
            'to_date' => $fromDate->addDay()->format('Ymd'),
        ])->onQueue('crawling');
    }

    public function getItems(int $limit, int $id = null): Collection
    {
        return $this->repository->getItemsByState($limit, $id);
    }
}
