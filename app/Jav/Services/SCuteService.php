<?php

namespace App\Jav\Services;

use App\Core\Services\Facades\Application;
use App\Jav\Crawlers\SCuteCrawler;
use App\Jav\Events\SCute\SCuteCompleted;
use App\Jav\Models\SCute;
use App\Jav\Models\State;
use App\Jav\Repositories\SCuteRepository;
use Illuminate\Support\Facades\Event;

class SCuteService
{
    public const SERVICE_NAME = 'scute';

    public const BASE_URL = 'http://www.s-cute.com/';
    public const DEFAULT_TOTAL_PAGES = 121;

    public const QUEUE_NAME = 'crawling';

    public function __construct(protected SCuteCrawler $crawler, protected SCuteRepository $repository)
    {
    }

    public function release()
    {
        /**
         * Release only fetch links another job will fetch detail later.
         */
        $currentPage = Application::getInt(self::SERVICE_NAME, 'current_page', 1);
        $totalPages = Application::getInt(self::SERVICE_NAME, 'total_pages', self::DEFAULT_TOTAL_PAGES);

        $items = $this->crawler->getItemLinks('contents', ['page' => $currentPage]);

        if ($items->isEmpty()) {
            return $items;
        }

        $items->each(function ($item) {
            $this->repository->create($item);
        });

        ++$currentPage;
        if ($totalPages < $currentPage) {
            $currentPage = 1;
        }

        Application::setSetting(self::SERVICE_NAME, 'current_page', $currentPage);

        return $items;
    }

    public function item(SCute $model)
    {
        $images = $this->crawler->getItem(str_replace(self::BASE_URL, '', $model->url));

        if ($images->isEmpty()) {
            return;
        }

        $model->update([
            'images' => $images->toArray(),
            'state_code' => State::STATE_COMPLETED,
        ]);

        Event::dispatch(new SCuteCompleted($model));
    }
}
