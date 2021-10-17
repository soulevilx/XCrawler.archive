<?php

namespace App\Jav\Services;

use App\Core\Models\State;
use App\Core\Services\ApplicationService;
use App\Jav\Crawlers\R18Crawler;
use App\Jav\Models\R18;
use App\Jav\Services\Interfaces\ServiceInterface;
use App\Jav\Services\Traits\HasAttributes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class R18Service implements ServiceInterface
{
    use HasAttributes;

    protected R18 $model;

    public function __construct(protected R18Crawler $crawler)
    {
    }

    public function create(): R18
    {
        $this->defaultAttribute('state_code', State::STATE_INIT);

        $this->model = R18::updateOrCreate(
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
        return $this->refetch($model);
    }

    public function release(string $type = 'release')
    {
        /**
         * Release only fetch links another job will fetch detail later.
         */
        $key = $type . '_current_page';
        $currentPage = ApplicationService::getConfig('r18', $key, 1);
        $url = R18::MOVIE_URLS[$type] . '/page=' . $currentPage;

        $items = $this->crawler->getItemLinks($url);

        if ($items->isEmpty()) {
            return $items;
        }

        $items->each(function ($item) {
            $this->setAttributes($item)->create();
        });

        ++$currentPage;
        if ((int) ApplicationService::getConfig('r18', $type . '_total_pages', 2000) < $currentPage) {
            $currentPage = 1;
        }

        ApplicationService::setConfig('r18', $key, $currentPage);

        return $items;
    }

    public function daily(string $type = 'release')
    {
        /**
         * Make sure we fetch page 1 to get latest release while `release` fetching older.
         */
        $items = $this->crawler->getItemLinks(R18::MOVIE_URLS[$type] . '/page=1');

        if ($items->isEmpty()) {
            return $items;
        }

        $items->each(function ($item) {
            $this->setAttributes($item)->create();
        });

        return $items;
    }

    public function refetch(R18 $model): R18
    {
        // Can't get item
        if (!$item = $this->crawler->getItem($model->content_id)) {
            return $model;
        }

        $item['runtime'] = $item['runtime_minutes'];
        $item['release_date'] = Carbon::createFromFormat('Y-m-d H:m:s', $item['release_date']);

        $item['maker'] = $item['maker']['name'] ?? null;
        $item['label'] = $item['label']['name'] ?? null;

        $item['series'] = $item['series'] ? $item['series']['name'] : [];

        if (is_array($item['categories'])) {
            foreach ($item['categories'] as $genre) {
                $item['genres'][] = $genre['name'];
            }
        }

        if (is_array($item['actresses'])) {
            foreach ($item['actresses'] as $performer) {
                $item['performers'][] = $performer['name'];
            }
        }

        if (is_array($item['channels'])) {
            foreach ($item['channels'] as $channel) {
                $item['channels'][] = $channel['name'];
            }
        }

        //$item['url'] = $item['detail_url'];
        $item['cover'] = $item['cover'] ?? $item['images']['jacket_image']['large'];

        if ($item) {
            $model->update($item);
        }

        return $model;
    }
}
