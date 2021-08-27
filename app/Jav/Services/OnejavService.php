<?php

namespace App\Jav\Services;

use App\Core\Services\ApplicationService;
use App\Jav\Crawlers\OnejavCrawler;
use App\Jav\Models\Onejav;
use App\Jav\Services\Traits\HasAttributes;

class OnejavService
{
    use HasAttributes;
    protected Onejav $model;

    public function __construct(protected OnejavCrawler $crawler, protected ApplicationService $service)
    {
    }

    public function create(): Onejav
    {
        $this->model = Onejav::firstOrCreate(
            ['dvd_id' => $this->attributes['dvd_id']],
            $this->attributes
        );

        return $this->model;
    }

    public function daily()
    {
        $items = $this->crawler->daily();
        if ($items->isEmpty()) {
            return $items;
        }

        $items->each(function ($item) {
            Onejav::updateOrCreate(
                [
                    'url' => $item->url,
                ],
                $item->getArrayCopy()
            );
        });

        return $items;
    }

    public function release()
    {
        $this->service->refresh();
        $currentPage = $this->service->get('onejav', 'current_page', 1);
        $items = $this->crawler->getItems('new', ['page' => $currentPage]);

        $items->each(function ($item) {
            Onejav::updateOrCreate(
                [
                    'url' => $item->url,
                ],
                $item->getArrayCopy()
            );
        });

        ++$currentPage;

        if ((int) $this->service->get('onejav', 'total_pages') < $currentPage) {
            $currentPage = 1;
        }

        $this->service->save('onejav', 'current_page', $currentPage);

        return $items;
    }
}
