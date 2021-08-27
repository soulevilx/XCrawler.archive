<?php

namespace App\Jav\Services;

use App\Core\Models\State;
use App\Core\Services\ApplicationService;
use App\Jav\Crawlers\R18Crawler;
use App\Jav\Models\R18;
use App\Jav\Services\Interfaces\ServiceInterface;
use App\Jav\Services\Traits\HasAttributes;
use Illuminate\Database\Eloquent\Model;

class R18Service implements ServiceInterface
{
    use HasAttributes;

    protected R18 $model;

    public function __construct(protected R18Crawler $crawler, protected ApplicationService $application)
    {
    }

    public function create(): R18
    {
        $this->defaultAttribute('state_code', State::STATE_INIT);

        $this->model = R18::firstOrCreate(
            [
                'url' => $this->attributes['url'],
                'content_id' => $this->attributes['content_id'],
            ],
            $this->attributes
        );

        return $this->model;
    }

    public function item(Model $model): R18
    {
        return $model->refetch();
    }

    public function release()
    {
        /**
         * Release only fetch links another job will fetch detail later.
         */
        $currentPage = $this->application->get('r18', 'current_page', 1);
        $url = R18::MOVIE_LIST_URL.'/page='.$currentPage;

        $items = $this->crawler->getItemLinks($url);

        if ($items->isEmpty()) {
            return $items;
        }

        $items->each(function ($item) {
            $this->setAttributes($item)->create();
        });

        ++$currentPage;
        if ((int) $this->application->get('r18', 'total_pages', 2000) < $currentPage) {
            $currentPage = 1;
        }

        $this->application->save('r18', 'current_page', $currentPage);

        return $items;
    }

    public function daily()
    {
        /**
         * Make sure we fetch page 1 to get latest release while `release` fetching older.
         */
        $items = $this->crawler->getItemLinks(R18::MOVIE_LIST_URL.'/page=1');

        if ($items->isEmpty()) {
            return $items;
        }

        $items->each(function ($item) {
            $this->setAttributes($item)->create();
        });

        return $items;
    }
}
